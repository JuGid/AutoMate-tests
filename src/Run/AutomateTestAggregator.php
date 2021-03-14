<?php 

namespace AutomateTest\Run;

use AutomateTest\Exception\ClassLoadException;
use AutomateTest\Group\TestGroupBatch;

class AutomateTestAggregator {

    public function collectionOfTestsFrom(string $directory) : array 
    {
        $batch = new TestGroupBatch($directory);
        $all_tests = [];

        foreach($batch as $testGroup) {

            if(!in_array($testGroup->getClassname(), get_declared_classes())) {
                throw new ClassLoadException(sprintf("Class %s is not loaded when trying to get tests builder", $testGroup->getClassname()));
            }

            array_push($all_tests, $testGroup->getTestBuilders());            
        }

        return $all_tests;
    }
    
}