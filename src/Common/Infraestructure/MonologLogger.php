<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Common\Infraestructure;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLog;
use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 *
 * @author nacho
 */
class MonologLogger implements Logger{
    
    private $absolute_max_logs = 100;
    private $absolute_min_logs = 1;
    
    /**
     * 
     * @var array<MonologLog>
     */
    private $monologLoggers = [];
    
    /**
     * 
     * @return MonologLog
     */
    private function getMonolog($channel): MonologLog
    {
        if(!isset($this->monologLoggers[$channel]))
        {
            $o = new MonologLog($channel);
            $cfg_max_day_logs = (int) getenv('LOGS_MAX_FILES');
            $max_day_logs = min([$this->absolute_max_logs,max([$this->absolute_min_logs, $cfg_max_day_logs])]);
            $path = getenv('LOGS_PATH');
            $rotate = new RotatingFileHandler("$path/default.log", $max_day_logs);
            $o->pushHandler($rotate);
            $this->monologLoggers[$channel] = $o;
        }        
        return $this->monologLoggers[$channel];
    }
    
    private function log($level, $message, array $context = array()): void {
        $trace = debug_backtrace();
        $parts = explode("\\",$trace[2]['class']);
        $class = end($parts);

        $this->getMonolog($class)->log($level, $message, $context);
    }

    //put your code here
    public function alert($message, array $context = array()): void {
        $this->log(MonologLog::ALERT, $message, $context);
    }

    public function critical($message, array $context = array()): void {
        $this->log(MonologLog::CRITICAL, $message, $context);
    }

    public function debug($message, array $context = array()): void {
        $this->log(MonologLog::DEBUG, $message, $context);
    }

    public function emergency($message, array $context = array()): void {
        $this->log(MonologLog::EMERGENCY, $message, $context);
    }

    public function error($message, array $context = array()): void {
        $this->log(MonologLog::ERROR, $message, $context);
    }

    public function info($message, array $context = array()): void {
        $this->log(MonologLog::INFO, $message, $context);
    }

    public function notice($message, array $context = array()): void {
        $this->log(MonologLog::NOTICE, $message, $context);
    }

    public function warning($message, array $context = array()): void {
        $this->log(MonologLog::WARNING, $message, $context);
    }
}
