<?php 

namespace AutomateTest\Aggregator;

use Automate\Handler\ErrorHandler;

class ErrorHandlerAggregator {

    private $errorsHandled = [];

    private $nbErrors = 0;

    public function addErrorHandler(ErrorHandler $handler, string $fromClass) {
        $this->nbErrors += $handler->countErrors();

        $this->errorsHandled[$fromClass] = $handler;
    }

    public function testFailed() : bool {
        return $this->nbErrors > 0;
    }

    public function printErrors() : void {

    }
}