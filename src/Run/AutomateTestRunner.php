<?php 

namespace AutomateTest\Run;

use Automate\AutoMate;
use AutomateTest\Aggregator\ErrorHandlerAggregator;
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

    public function __construct() {
        $this->state = self::STATE_UNREADY;
        $this->testState = self::WIN;

        $this->errorHandlerAggregator = new ErrorHandlerAggregator();
        $this->automateTestAggregator = new AutomateTestAggregator();
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

            foreach($tests as $test) {
                $automate = new AutoMate($test->get('configuration_filepath'));
                $errors = $automate->run($test->getScenarioName(),false,true,$test->get('browser'));

                if($errors) {
                    $this->errorHandlerAggregator->addErrorHandler($errors, $test->get('classname'));
                }
            }

            $this->state = self::STATE_FINISHED;
        } catch(Exception $e) {
            echo $e->getMessage()."\n";
            $this->testState = self::FAIL;
        }

        if($this->state == self::STATE_FINISHED && $this->errorHandlerAggregator->testFailed()) {
            $this->testState = self::FAIL;
            echo "RESULT \n";
        }
        
        exit($this->testState);
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