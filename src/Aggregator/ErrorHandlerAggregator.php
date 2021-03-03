<?php 

namespace AutomateTest\Aggregator;

use Automate\Handler\ErrorHandler;

class ErrorHandlerAggregator {

    private $errorsHandled = [];

    private $nbErrors = 0;

    /**
     * @todo Should instanciate ErrorHandlerExtended
     */
    public function addErrorHandler(ErrorHandler $handler, string $fromClass, string $withMethod) {
        $this->nbErrors += $handler->countErrors();

        $this->errorsHandled[] = new ErrorHandlerExtended($handler, $fromClass, $withMethod);
    }

    public function testFailed() : bool {
        return $this->nbErrors > 0;
    }

    public function getErrorsHandled() : array {
        return $this->errorsHandled;
    }

    public function getCountErrors() : int {
        return $this->nbErrors;
    }

}