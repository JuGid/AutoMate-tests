<?php 

namespace AutomateTest\Group;

use ReflectionClass;
use ReflectionMethod;

class TestGroup {

    /**
     * @var ReflectionClass
     */
    private $class = null;

    /**
     * Array of all methods from the class
     * @var array
     */
    private $methods = [];

    public function __construct(ReflectionClass $class)
    {
        $this->class = $class;
        $this->methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    
}