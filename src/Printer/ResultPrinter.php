<?php

namespace AutomateTest\Printer;

use Automate\Console\Console;
use AutomateTest\Aggregator\ErrorHandlerAggregator;
use AutomateTest\Aggregator\ErrorHandlerExtended;
use AutomateTest\Exception\PrinterException;

class ResultPrinter {

    private $errors = [];

    public function __construct(ErrorHandlerAggregator $eha) 
    {
        $this->errors = $eha->getErrorsHandled();
        $this->nbErrors = $eha->getCountErrors();
    }

    public function compare(ErrorHandlerExtended $a, ErrorHandlerExtended $b) {
        return strtolower($a->getClassname()) <=> strtolower($b->getClassname());
    }

    public function print() {
        if(empty($this->errors)) {
            throw new PrinterException('You are trying to write errors but there is not');
        }

        usort($this->errorsHandled, 'compare');

        Console::separator('=');
        Console::writeln("  _____ _____ _____ _____ _____ _____ ");
        Console::writeln(" | __  |   __|  _  |     | __  |_   _|");
        Console::writeln(" |    -|   __|   __|  |  |    -| | |  ");
        Console::writeln(" |__|__|_____|__|  |_____|__|__| |_|  ");
        Console::separator("=");

        foreach($this->errorsHandled as $errorHandlerExtended) {
            Console::writeln('In test class '. $errorHandlerExtended->getClassname());
            $errorHandlerExtended->getErrorHandler()->printErrorsTypeOnly();
        }

        Console::writeln(sprintf('Errors thrown. Total : %d', $this->nbErrors));
    }
}