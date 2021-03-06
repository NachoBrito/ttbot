<?php
declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Infraestructure\ConsoleLogger;
use PHPUnit\Framework\TestCase;



/**
 * 
 *
 * @author nacho
 */
class ConsoleLoggerTest extends TestCase {

    public function testLevels() {
        $message = 'test message';
        $context = [];
        
        $logger = new ConsoleLogger();
        ob_start();
        $logger->alert($message, $context);
        self::assertSame("[ConsoleLoggerTest][ALERT] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->critical($message, $context);
        self::assertSame("[ConsoleLoggerTest][CRITICAL] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->debug($message, $context);
        self::assertSame("[ConsoleLoggerTest][DEBUG] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->emergency($message, $context);
        self::assertSame("[ConsoleLoggerTest][EMERGENCY] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->error($message, $context);
        self::assertSame("[ConsoleLoggerTest][ERROR] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->info($message, $context);
        self::assertSame("[ConsoleLoggerTest][INFO] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->notice($message, $context);
        self::assertSame("[ConsoleLoggerTest][NOTICE] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
        ob_start();
        $logger->warning($message, $context);
        self::assertSame("[ConsoleLoggerTest][WARNING] $message " . json_encode($context) . "\n",ob_get_contents());
        ob_end_clean();
        
    }

}
