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

    private function getTag($caller) {
        
    }

    //put your code here
    public function alert($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][ALERT] $message " . json_encode($context) . "\n";
    }

    public function critical($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][CRITICAL] $message " . json_encode($context) . "\n";
    }

    public function debug($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][DEBUG] $message " . json_encode($context) . "\n";
    }

    public function emergency($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][EMERGENCY] $message " . json_encode($context) . "\n";
    }

    public function error($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][ERROR] $message " . json_encode($context) . "\n";
    }

    public function info($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][INFO] $message " . json_encode($context) . "\n";
    }

    public function notice($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][NOTICE] $message " . json_encode($context) . "\n";
    }

    public function warning($message, array $context = array()): void {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];

        echo "[$class][WARNING] $message " . json_encode($context) . "\n";
    }

}
