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
        if($this->state == self::STATE_DIRTY) {
            throw new BuilderException('Builder state is dirty. Something is missing.');
        }

        return $this;
    }

    /**
     * Inspired by the doctrine QueryBuilder
     */
    private function add(string $option, $value, bool $append = false) {
        if($append) {
            $this->arguments[$option][] = $value;
        } else {
            $this->arguments[$option] = $value;
        }

        if(isset($this->arguments['configuration_filepath'])) {
            $this->state = self::STATE_ALMOST;
        }

        if($this->state == self::STATE_ALMOST && isset($this->arguments['classname'])) {
            $this->state = self::STATE_CLEAN;
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

    public function stopOnError() {
        return $this->add('stop_on_error', true, false);
    }

    public function printOptionsAtEnd() {
        return $this->add('print_options_at_end', true, false);
    }

    public function writeOnStdError() {
        return $this->add('write_on_std_error', true, false);
    }

    public function repeatTestFor(int $time) {
        return $this->add('repeat_test_for', $time, false);
    }

    public function createReportFile(string $type = 'xml') {
        return $this->add('report_file_type', $type, false);
    }

    public function printErrorsFlat() {
        return $this->add('print_errors_flat', true, false);
    }

    public function shouldThrowMessage(string $message) {
        return $this->add('should_throw_message', $message, false);
    }

    public function shouldThrowError(string $errorClass) {
        return $this->add('should_throw_error', $errorClass, false);
    }

    public function datasetShouldThrowError(array $dataset) {
        return $this->add('dataset_should_throw_error', $dataset, false);
    }

    public function inClass(string $class) {
        return $this->add('classname', $class, false);
    }

    public function getScenarioName() : string {
        return $this->scenario;
    }

    
}