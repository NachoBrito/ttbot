<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 *
 * @author nacho
 */
class BufferedLogger implements Logger {

    /**
     * 
     * @var array<string>
     */
    private $log = [];
    private function getTag($caller) {
        
    }

    //put your code here
    public function alert($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][ALERT] $message " . json_encode($context) . "\n";
    }

    public function critical($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][CRITICAL] $message " . json_encode($context) . "\n";
    }

    public function debug($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][DEBUG] $message " . json_encode($context) . "\n";
    }

    public function emergency($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][EMERGENCY] $message " . json_encode($context) . "\n";
    }

    public function error($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][ERROR] $message " . json_encode($context) . "\n";
    }

    public function info($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][INFO] $message " . json_encode($context) . "\n";
    }

    public function notice($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][NOTICE] $message " . json_encode($context) . "\n";
    }

    public function warning($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        $this->log[] = "[$class][WARNING] $message " . json_encode($context) . "\n";
    }

    
    /**
     * 
     * @return type
     */
    public function flushLog() {
        $log = $this->log;
        $this->log = [];
        
        return $log;
    }


}
