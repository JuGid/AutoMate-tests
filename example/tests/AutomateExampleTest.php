<?php 

namespace AutomateTest;

use AutomateTest\AutoMateTest;
use InvalidArgumentException;

class AutomateExampleTest extends AutoMateTest {

    const CONFIG_FILE = __DIR__.'/../config/config-test.yaml';

    public function testShouldTestSimpleScenario() {
        return $this->createTestBuilder('simple')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->printOptionsAtEnd();
    }

    public function testShouldThrowErrorThatDoesNotRespectTheConditionClass() {
        return $this->createTestBuilder('simple-error')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->shouldThrowError(InvalidArgumentException::class);
    }

    public function testShouldTestRepeat() {
        return $this->createTestBuilder('simple-error')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->repeatTestFor(2);
    }

    public function testShouldThrowErrorThatDoesNotRespectTheConditionMessage() {
        return $this->createTestBuilder('simple-error')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->shouldThrowMessage('Title is not Youtube');
    }
}