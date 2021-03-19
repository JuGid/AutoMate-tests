<?php 

namespace AutomateTest;

use AutomateTest\AutoMateTest;
use Facebook\WebDriver\Exception\TimeoutException;

class AutomateExampleTest extends AutoMateTest {

    const CONFIG_FILE = __DIR__.'/../config/config-test.yaml';

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilder() {
        return $this->createTestBuilder('simple')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE);
    }

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilderOtherForm() {
        return $this->createTestBuilder('simple-error')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->shouldThrowError(TimeoutException::class)
                    ->printOptionsAtEnd();
    }

    public function testShouldTestRepeat() {
        return $this->createTestBuilder('simple-error')
                    ->withChrome()
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->repeatTestFor(2)
                    ->printOptionsAtEnd();
    }
}