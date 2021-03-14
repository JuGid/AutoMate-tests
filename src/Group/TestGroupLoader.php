<?php 

namespace AutomateTest\Group;

use AutomateTest\Exception\ClassLoadException;
use ReflectionClass;
use ReflectionException;

class TestGroupLoader 
{
    
    public static $loadedClasses = [];

    public static $declaredClasses = [];

    public function load(string $classFile, string $className) {
        if(!class_exists($className)) {
            include_once($classFile);

            $loadedClasses = array_values(
                array_diff(get_declared_classes(), array_merge(self::$declaredClasses, self::$loadedClasses))
            );

            self::$loadedClasses = array_merge($loadedClasses, self::$loadedClasses);

            if (empty(self::$loadedClasses)) {
                throw new ClassLoadException(sprintf("Class %s could not be found or load in %s", $className, $classFile));
            }
        }

        try {
            $class = new ReflectionClass($className);
        } catch(ReflectionException $e) {
            throw new \Exception(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
        
        if(!$class->isSubclassOf(AutomateTest::class) || $class->isAbstract()) {
            throw new ClassLoadException(sprintf("Class %s could not be found in %s", $className, $classFile));
        }

        return new TestGroup($class, $className);
    }

}