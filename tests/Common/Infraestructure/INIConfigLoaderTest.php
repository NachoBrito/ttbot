<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class INIConfigLoaderTest extends TestCase {

    public function testConfig() {
        putenv('TEST_CONFIG_KEY_1');
        putenv('TEST_CONFIG_KEY_2');

        self::assertEmpty(getenv('TEST_CONFIG_KEY_1'));
        self::assertEmpty(getenv('TEST_CONFIG_KEY_2'));
        
        $config = new INIConfigLoader(__DIR__);
        $config->load();
        
        self::assertSame('TEST_VALUE_1', getenv('TEST_CONFIG_KEY_1'));
        self::assertSame('TEST_VALUE_LOCAL', getenv('TEST_CONFIG_KEY_2'));

        putenv('TEST_CONFIG_KEY_1');
        putenv('TEST_CONFIG_KEY_2');
    }

}
