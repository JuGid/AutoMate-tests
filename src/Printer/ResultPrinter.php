<?php

namespace AutomateTest\Printer;

use Automate\Console\Console;
use AutomateTest\Aggregator\ErrorHandlerAggregator;
use AutomateTest\Aggregator\ErrorHandlerExtended;
use AutomateTest\Exception\PrinterException;

class ResultPrinter {

    public function compare(ErrorHandlerExtended $a, ErrorHandlerExtended $b) {
        return strtolower($a->getClassname()) <=> strtolower($b->getClassname());
    }

    public function print(ErrorHandlerAggregator $eha) : void {
        Console::separator('=');
        Console::writeln("  _____ _____ _____ _____ _____ _____ ");
        Console::writeln(" | __  |   __|  _  |     | __  |_   _|");
        Console::writeln(" |    -|   __|   __|  |  |    -| | |  ");
        Console::writeln(" |__|__|_____|__|  |_____|__|__| |_|  ");
        Console::separator("=");

        /*
        if(empty($this->errors)) {
            Console::writeln('NO ERROR', 'green');
            return;
        }

        usort($this->errorsHandled, 'compare');

        foreach($this->errorsHandled as $errorHandlerExtended) {
            Console::writeln(sprintf('In test class %s, in method %s',
                $errorHandlerExtended->getClassname(),
                $errorHandlerExtended->getMethodname()
            ));

            $errorHandlerExtended->getErrorHandler()->printErrorsTypeOnly();
        }

        Console::writeln(sprintf('Errors thrown. Total : %d', $this->nbErrors));
        */
    }
}