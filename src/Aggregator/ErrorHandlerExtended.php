<?php 

namespace AutomateTest\Aggregator;

use Automate\Handler\ErrorHandler;

class ErrorHandlerExtended {
    
    private $errorHandler = null;

    private $class = '';

    private $method = '';

    public function __construct(ErrorHandler $errorHandler, string $class, string $method)
    {
        $this->errorHandler = $errorHandler;
        $this->class = $class;
        $this->method = $method;
    }

    public function getClassname() : string {
        return $this->class;
    }

    public function getMethodname() : string {
        return $this->method;
    }

    public function getErrorHandler() : ErrorHandler {
        return $this->errorHandler;
    }
    
}