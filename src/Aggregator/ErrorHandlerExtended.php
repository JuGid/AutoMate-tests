<?php 

namespace AutomateTest\Aggregator;

use Automate\Handler\ErrorHandler;
use AutomateTest\AutomateTestBuilder;

class ErrorHandlerExtended 
{
    
    private $errorHandler = null;

    private $testBuilder = null;

    public function __construct(ErrorHandler $errorHandler, AutomateTestBuilder $testBuilder)
    {
        $this->errorHandler = $errorHandler;
        $this->testBuilder = $testBuilder;
    }

    public function getClassname() : string {
        return $this->testBuilder->get('classname');;
    }

    public function getMethodname() : string {
        return $this->testBuilder->get('method_name');
    }

    public function getErrorHandler() : ErrorHandler {
        return $this->errorHandler;
    }

    public function getTestbuilder() : AutomateTestBuilder {
        return $this->testBuilder;
    }

    public function respect(string $condition, string $value) {

        if(empty($this->getTestBuilder()->get($condition))) {
            return true;
        }

        return $value == $this->getTestBuilder()->get($condition);
    }
    
}