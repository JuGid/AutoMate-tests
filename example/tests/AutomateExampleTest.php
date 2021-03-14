<?php 

namespace AutomateTest;

use AutomateTest\AutoMateTest;
use Facebook\WebDriver\Exception\TimeoutException;

class AutomateExampleTest extends AutoMateTest {

    const CONFIG_FILE = __DIR__.'/../config/config-test.yaml';

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilder() {
        return $this->createTestBuilder('simple')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->shouldThrowError(TimeoutException::class)
                    ->printOptionsAtEnd();
    }

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilderOtherForm() {
        $builder = new AutomateTestBuilder('simple');
        $builder->withAutomateConfigurationFile(self::CONFIG_FILE);
        $builder->withChrome();
        $builder->shouldThrowError(TimeoutException::class);
        $builder->printOptionsAtEnd();
        return $builder; 
    }
}