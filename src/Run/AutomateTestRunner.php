<?php 

namespace AutomateTest\Run;

use Automate\AutoMate;
use Automate\Configuration\Configuration;
use Automate\Console\Console;
use AutomateTest\Aggregator\ErrorHandlerAggregator;
use AutomateTest\Listener\ScreenshotMaker;
use AutomateTest\Printer\ResultPrinter;
use Exception;

class AutomateTestRunner {

    const WIN = 0;
    const FAIL = 1;

    const STATE_UNREADY = 0;
    const STATE_ALMOST = 1;
    const STATE_READY = 2;
    const STATE_FINISHED = 3;

    /**
     * @var ErrorHandlerAggregator
     */
    private $errorHandlerAggregator = null;

    /**
     * @var AutoMate
     */
    private $automate = null;

    /**
     * @var AutomateTestAggregator
     */
    private $automateTestAggregator = null;

    /**
     * State of the running TestRunner
     */
    public $state;

    /**
     * State of the test in its wholeness
     */
    private $testState;

    /**
     * Directory where to find AutomateTest classes
     */
    private $directory = '/';

    /**
     * @var ResultPrinter
     */
    private $printer = null;

    public function __construct() {
        $this->state = self::STATE_UNREADY;
        $this->testState = self::WIN;

        $this->errorHandlerAggregator = new ErrorHandlerAggregator();
        $this->automateTestAggregator = new AutomateTestAggregator();
        $this->printer = new ResultPrinter();
    }

    public function testsAreIn(string $directory) {
        $this->directory = $directory;
        $this->state = self::STATE_READY;
        return $this;
    }

    public function run() {

        if($this->state == self::STATE_UNREADY || $this->state == self::STATE_ALMOST) {
            echo "You should use AutomateTestRunner::testsAreIn before ::run";
            exit(self::FAIL);
        }

        $this->state = self::STATE_READY;

        try {
            $tests = $this->automateTestAggregator->collectionOfTestsFrom($this->directory);
        } catch(Exception $e) {
            Console::writeEx($e);
            exit(1);
        }

        $totalTests = 0;
        foreach($tests as $testbuilder) {
            $numberOfTests = $this->runTestForTestBuilders($testbuilder);
            
            $totalTests+=$numberOfTests;
        }

        if($this->errorHandlerAggregator->testFailed()) {
            $this->testState = self::FAIL;
        }

        $this->state = self::STATE_FINISHED;
        $this->printer->print($this->errorHandlerAggregator, $totalTests);

        exit($this->testState);
    }

    private function runTestForTestBuilders(array $testBuilder) : int {
        $numberOfTests = 0;
        foreach($testBuilder as $test) {
            $this->automate = new AutoMate($test->get('configuration_filepath'));
            $this->automate->doNotPrint();

            $screenshotsPath =  Configuration::get('scenario.folder') . '/' . $test->getScenarioName();

            //$this->automate->registerPlugin(new ScreenshotMaker($screenshotsPath));

            for($i=0; $i < $test->get('repeat_test_for'); $i++) {

                $error = $this->automate->run($test->getScenarioName(),false,true,$test->get('browser'));

                //AutoMate returns False if an error occured when creating the scenario/runner/logger
                //Returning an errorHandler doesn't mean that there is no error.
                if($error) {
                    $this->errorHandlerAggregator->addErrorHandler($error, $test);
                }

                $numberOfTests++;
            }
        }
        return $numberOfTests;
    }

    public function getState() : string {
        return $this->convertStateToString($this->state);
    }

    private function convertStateToString(int $state) : string {
        $stateStr = '';
        switch($state) {
            case self::STATE_UNREADY:
                $stateStr = 'UNREADY';
                break;
            case self::STATE_ALMOST:
                $stateStr = 'ALMOST';
                break;
            case self::STATE_READY:
                $stateStr = 'READY';
                break;
            case self::STATE_FINISHED:
                $stateStr = 'FINISHED';
                break;
            default:
                $stateStr = 'UNDEFINED';
                break;
        }
        return sprintf('Runner state is [%s]', $stateStr);
    }
}