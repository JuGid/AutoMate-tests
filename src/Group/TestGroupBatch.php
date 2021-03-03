<?php 

namespace AutomateTest\Group;

use AutomateTest\Exception\BatchException;
use Exception;
use Iterator;

class TestGroupBatch implements Iterator{

    private $batchIndex = 0;

    private $batch = [];

    private $loader = null;

    private $directory = '/';

    public function __construct(string $directory)
    {
        $this->loader = new TestGroupLoader();
        $this->mapper = new ClassMapper();
        $this->directory = $directory;

        try{
            $this->createBatch($directory);
        } catch(Exception $e) {
            throw new Exception(sprintf('Batch of test threw an exception : %s', $e->getMessage()));
        }
        
    }

    private function createBatch(string $directory) {
        $directoryTestsMap = $this->mapper->getMap($directory);

        if(empty($directoryTestsMap)) {
            throw new BatchException('Batch is empty');
        }

        foreach($directoryTestsMap as $file=>$class) {
            $this->addFromClassFileAndName($file, $class);
        }
    }

    public function addFromClassFileAndName(string $classFile, string $className) : void {
        $testGroup = $this->loader->load($classFile, $className);
        $this->addTestGroup($testGroup);
    }

    public function addTestGroup(TestGroup $testGroup) {
        $this->batch[] = $testGroup;
    }

    public function rewind() {
        $this->batchIndex = 0;
    }

    public function current() {
        return $this->batch[$this->batchIndex];
    }

    public function key() {
        return $this->batchIndex;
    }

    public function next() {
        ++$this->batchIndex;
    }

    public function valid() {
        return isset($this->batch[$this->batchIndex]);
    }
}