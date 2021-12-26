<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 *
 * @author nacho
 */
class ConsoleLogger implements Logger {

    private function log($level, $message, array $context = array()): void {
        $trace = debug_backtrace();
        $parts = explode("\\",$trace[2]['class']);
        $class = end($parts);

        echo "[$class][$level] $message " . json_encode($context) . "\n";        
    }

    //put your code here
    public function alert($message, array $context = array()): void {
        $this->log('ALERT', $message, $context);
    }

    public function critical($message, array $context = array()): void {
        $this->log('CRITICAL', $message, $context);
    }

    public function debug($message, array $context = array()): void {
        $this->log('DEBUG', $message, $context);
    }

    public function emergency($message, array $context = array()): void {
        $this->log('EMERGENCY', $message, $context);
    }

    public function error($message, array $context = array()): void {
        $this->log('ERROR', $message, $context);
    }

    public function info($message, array $context = array()): void {
        $this->log('INFO', $message, $context);
    }

    public function notice($message, array $context = array()): void {
        $this->log('NOTICE', $message, $context);
    }

    public function warning($message, array $context = array()): void {
        $this->log('WARNING', $message, $context);
    }

}
