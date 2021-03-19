<?php

namespace AutomateTest\Printer;

use Automate\Console\Console;
use AutomateTest\Aggregator\ErrorHandlerAggregator;
use AutomateTest\Aggregator\ErrorHandlerExtended;
use AutomateTest\Exception\PrinterException;

class ResultPrinter {

    public static function compare(ErrorHandlerExtended $a, ErrorHandlerExtended $b) {
        return strtolower($a->getClassname()) <=> strtolower($b->getClassname());
    }

    public function print(ErrorHandlerAggregator $eha, int $testCount) : void {
        Console::separator('=');
        Console::writeln("  _____ _____ _____ _____ _____ _____ ");
        Console::writeln(" | __  |   __|  _  |     | __  |_   _|");
        Console::writeln(" |    -|   __|   __|  |  |    -| | |  ");
        Console::writeln(" |__|__|_____|__|  |_____|__|__| |_|  ");
        Console::separator("=");
        Console::writeln('');

        
        if(!$eha->testFailed()) {
            Console::writeln('NO ERROR', 'green');
        } else {

            //Not needed to sort errors handlers because the class mapper is already ordered

            foreach($eha->getErrorsHandled() as $errorHandlerEx) {

                $errorHandler = $errorHandlerEx->getErrorHandler();
                $testBuilder = $errorHandlerEx->getTestbuilder();
                
                if($errorHandlerEx->getErrorHandler()->countErrors() == 0 ) {
                    continue;
                }

                $error = $errorHandler->getErrors()[0];

                Console::writeln(sprintf('%s::%s',
                    $errorHandlerEx->getClassname(),
                    $errorHandlerEx->getMethodname()
                ));
                
                $message = "This scenario does not respect the condition %s : ";

                if(!$errorHandlerEx->respect('should_throw_error', $error->getExceptionClass())) {
                    Console::writeln(sprintf($message, $testBuilder->get('should_throw_error')));
                }

                if(!$errorHandlerEx->respect('should_throw_message', $error->getType())) {
                    Console::writeln(sprintf($message, $testBuilder->get('should_throw_message')));
                }

                $errorHandlerEx->getErrorHandler()->printErrors();
            }
    
            Console::writeln(sprintf("\n\tErrors thrown. Total : %d/%d\n", $eha->getCountErrors(), $testCount), $eha->getCountErrors() > 0 ? 'red': 'green');
        }        
    }
}