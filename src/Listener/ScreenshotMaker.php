<?php

namespace AutomateTest\Listener;

use Automate\AutoMateEvents;
use Automate\AutoMateListener;

class ScreenshotMaker implements AutoMateListener {

    private $screen_id = 0;

    private $screen_directory = '';

    public function __construct(string $path) {
        $this->screen_directory = sprintf('%s/screenshots/%s', $path, (new \DateTime('NOW'))->format('Y-m-d_hhii'));
        
        if(!is_dir($this->screen_directory)) {
            mkdir($this->screen_directory, 0777, true);
        }
        
    }

    public function onEvent() {
        return AutoMateEvents::STEP_TRANSFORM;
    }

    public function notify(string $event, $data) : int {
        $driver = $data['driver'];

        $driver->takeScreenshot($this->screen_directory . '/' . array_keys($data['step'])[0] . '_' . $this->screen_id . '.jpg');
        $this->screen_id += 1;

        return AutoMateListener::STATE_RECEIVED;
    }
}