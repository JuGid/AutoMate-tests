<?php 

namespace AutomateTest;

use AutomateTest\Exception\BuilderException;
use InvalidArgumentException;

final class AutomateTestBuilder {

    public const STATE_DIRTY = 0;
    public const STATE_ALMOST = 1;
    public const STATE_CLEAN = 2;
    
    private $state;

    private $scenario;

    private $arguments = [];

    public function __construct(string $scenario)
    {
        if(empty($scenario)) {
            throw new InvalidArgumentException('Scenario name should not be empty');
        }
        
        $this->scenario = $scenario;
        $this->state = self::STATE_DIRTY;
    }

    public function isClean() : bool {
        return $this->state == self::STATE_CLEAN;
    }

    /**
     * This function verify the state. This can permit to
     * add functionnalities in the future
     */
    public function build() {
        if(isset($this->arguments['configuration_filepath'])) {
            $this->state = self::STATE_ALMOST;
        }

        if($this->state == self::STATE_ALMOST){
            if(isset($this->arguments['method_name']) && isset($this->arguments['classname'])) {
                $this->state = self::STATE_CLEAN;
            }
        }

        if(!isset('data')) {
            $this->add('data',[],false);
        }
    
        if(!isset('browser')) {
            $this->add('browser',null,false);
        }

        if(!isset('should_throw_message')) {
            $this->add('should_throw_message', '', false);
        }

        if(!isset('should_throw_error')) {
            $this->add('should_throw_error', '', false);
        }

        if(!isset('print_data')) {
            $this->add('print_data', false, false);
        }

        if(!isset('print_options')) {
            $this->add('print_options', false, false);
        }

        if(!isset('repeat_test_for')) {
            $this->add('repeat_test_for', 1, false);
        }

        if($this->state == self::STATE_DIRTY) {
            throw new BuilderException('Builder state is dirty. Something is missing.');
        }

        if($this->state == self::STATE_ALMOST) {
            throw new BuilderException('Builder state is almost. Should set class and method names.');
        }

        return $this;
    }

    private function add(string $option, $value, bool $append = false) {
        if($append) {
            $this->arguments[$option][] = $value;
        } else {
            $this->arguments[$option] = $value;
        }

        return $this;
    }

    public function get(string $parameter) {
        if(!isset($this->arguments[$parameter])) {
            return null;
        }

        return $this->arguments[$parameter];
    }

    public function withAutomateConfigurationFile(string $filepath) {
        return $this->add('configuration_filepath', $filepath, false);
    }

    public function withData(array $dataset) {
        return $this->add('data', $dataset, false);
    }

    public function withBrowser(string $browser) {
        return $this->add('browser', $browser, false);
    }

    public function withFirefox() {
        return $this->add('browser', 'firefox', false);
    }

    public function withChrome() {
        return $this->add('browser', 'chrome', false);
    }

    public function shouldThrowMessage(string $message) {
        return $this->add('should_throw_message', $message, false);
    }

    public function shouldThrowError(string $errorClass) {
        return $this->add('should_throw_error', $errorClass, false);
    }

    public function printDataAtEnd() : self {
        return $this->add('print_data', true, false);
    } 

    public function printOptionsAtEnd() {
        return $this->add('print_options', true, false);
    }

    public function repeatTestFor(int $time) {
        return $this->add('repeat_test_for', $time, false);
    }

    public function inClass(string $class) {
        return $this->add('classname', $class, false);
    }

    public function inMethod(string $method) {
        return $this->add('method_name', $method, false);
    }

    public function getScenarioName() : string {
        return $this->scenario;
    }

    /* 

    This should be a global configuration, not in testbuilder

    public function stopOnError() {
        return $this->add('stop_on_error', true, false);
    }

    public function writeOnStdError() {
        return $this->add('write_on_std_error', true, false);
    }

    public function createReportFile(string $type = 'xml') {
        return $this->add('report_file_type', $type, false);
    }

    public function printErrorsFlat() {
        return $this->add('print_errors_flat', true, false);
    }
    */

    
}