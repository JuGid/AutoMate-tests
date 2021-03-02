<?php 

namespace AutomateTest;

use AutomateTest\AutoMateTest;
use Facebook\WebDriver\Exception\TimeoutException;

class AutomateExampleTest extends AutoMateTest {

    const CONFIG_FILE = '';

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilder() {
        return $this->createTestBuilder('scenario')
                    ->withAutomateConfigurationFile(self::CONFIG_FILE)
                    ->shouldThrowError(TimeoutException::class)
                    ->printOptionsAtEnd()
                    ->build();
    }

    public function testShouldSeeIfAutomateTestWorksWithAutomateBuilderOtherForm() {
        $builder = new AutomateTestBuilder('scenario');
        $builder->withAutomateConfigurationFile(self::CONFIG_FILE);
        $builder->withChrome();
        $builder->shouldThrowError(TimeoutException::class);
        $builder->printOptionsAtEnd();
        return $builder->build(); 
    }
}