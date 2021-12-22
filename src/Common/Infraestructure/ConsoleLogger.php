<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\LoggerInterface;

/**
 * 
 *
 * @author nacho
 */
class ConsoleLogger implements LoggerInterface{
    //put your code here
    public function alert($message, array $context = array()): void {
        echo "[ALERT] $message " . json_encode($context) . "\n";
    }

    public function critical($message, array $context = array()): void {
        echo "[CRITICAL] $message " . json_encode($context) . "\n";
    }

    public function debug($message, array $context = array()): void {
        echo "[DEBUG] $message " . json_encode($context) . "\n";
    }

    public function emergency($message, array $context = array()): void {
        echo "[EMERGENCY] $message " . json_encode($context) . "\n";
    }

    public function error($message, array $context = array()): void {
        echo "[ERROR] $message " . json_encode($context) . "\n";
    }

    public function info($message, array $context = array()): void {
        echo "[INFO] $message " . json_encode($context) . "\n";
    }

    public function notice($message, array $context = array()): void {
        echo "[NOTICE] $message " . json_encode($context) . "\n";
    }

    public function warning($message, array $context = array()): void {
        echo "[WARNING] $message " . json_encode($context) . "\n";
    }

}
