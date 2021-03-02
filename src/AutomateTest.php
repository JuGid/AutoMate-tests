<?php

namespace AutomateTest;

class AutomateTest {

    public function createTestBuilder(string $scenario) : AutomateTestBuilder{
        return new AutomateTestBuilder($scenario);
    }
}