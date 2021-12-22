<?php
declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;
use PHPUnit\Framework\TestCase;



/**
 * 
 *
 * @author nacho
 */
class BufferedLoggerTest extends TestCase {

    public function testLevels() {
        $message = 'test message';
        $context = [];
        
        $expected = [];
        $logger = new BufferedLogger();
        $logger->alert($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][ALERT] $message " . json_encode($context) . "\n";
        
        $logger->critical($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][CRITICAL] $message " . json_encode($context) . "\n";
        
        $logger->debug($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][DEBUG] $message " . json_encode($context) . "\n";
        
        $logger->emergency($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][EMERGENCY] $message " . json_encode($context) . "\n";
        
        $logger->error($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][ERROR] $message " . json_encode($context) . "\n";
        
        $logger->info($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][INFO] $message " . json_encode($context) . "\n";
        
        $logger->notice($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][NOTICE] $message " . json_encode($context) . "\n";
        
        $logger->warning($message, $context);
        $expected[] = "[NachoBrito\TTBot\Common\Infraestructure\BufferedLoggerTest][WARNING] $message " . json_encode($context) . "\n";
        
        self::assertSame($expected, $logger->flushLog());
        self::assertSame([], $logger->flushLog());
        
    }

}
