<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Domain\Model;

use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class LanguageTest extends TestCase {

    public function testGetAll() {
        $expected = [
            'en',
            'fr',
            'de',
            'it',
            'no',
            'ru',
            'es',
        ];
        self::assertSame($expected, Language::all());
    }

}
