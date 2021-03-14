<?php 

namespace AutomateTest\Group;

use AutomateTest\AutomateTestBuilder;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

class TestGroup 
{

    /**
     * @var ReflectionClass
     */
    private $class = null;

    private $className = '';

    public function __construct(ReflectionClass $class, string $className)
    {
        $this->class = $class;
        $this->className = $className;
    }

    public function getTestBuilders() : array {
        
        try {
            $class = $this->class->newInstance();
        } catch(ReflectionException $e) {
            throw new Exception(sprintf("The class %s cannot be instanciate", $this->class->getName()));
        }

        $builders = [];
        $methods = $this->class->getMethods();

        foreach($methods as $reflectionMethod) {
            if($reflectionMethod->class != get_class($class)) {
                continue;
            }
            

            $builder = call_user_func([$class, $reflectionMethod->getName()]);

            if(!$builder instanceof AutomateTestBuilder) {
                throw new RuntimeException(
                    sprintf("Method %s does not return an instance of %s",
                                $reflectionMethod->getName(),
                                AutomateTestBuilder::class));
            }

            $builder->inClass($this->className)
                    ->inMethod($reflectionMethod->getName())
                    ->build();

            if(!$builder->isClean()) {
                continue;
            }
            
            $builders[] = $builder;
        }

        return $builders;
    }

    public function getClassname() : string {
        return $this->class->getName();
    }
}