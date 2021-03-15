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

            foreach($eha->getErrorsHandled() as $errorHandlerExtended) {

                if($errorHandlerExtended->getErrorHandler()->countErrors() == 0 ) {
                    continue;
                }

                Console::writeln(sprintf('%s::%s',
                    $errorHandlerExtended->getClassname(),
                    $errorHandlerExtended->getMethodname()
                ));
                
                $errorHandlerExtended->getErrorHandler()->printErrors();
            }
    
            Console::writeln(sprintf("\nErrors thrown. Total : %d/%d", $eha->getCountErrors(), $testCount), $eha->getCountErrors() > 0 ? 'red': 'green');
        }        
    }
}