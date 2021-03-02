<?php 

namespace AutomateTest;

use AutomateTest\Aggregator\ErrorHandlerAggregator;

class AutomateTestRunner {

    const WIN = 0;
    const FAIL = 1;

    /**
     * @var ErrorHandlerAggregator
     */
    private $errorHandlerAggregator = null;

    public function __construct() {
        $this->errorHandlerAggregator = new ErrorHandlerAggregator();
    }

    public function run() {
        //Aggregation of TestGroups and run everything with configuration
        //Handle errors
        //Call ResultPrinter to show
    }
}