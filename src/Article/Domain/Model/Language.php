<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Domain\Model;

use ReflectionClass;

/**
 * 
 *
 * @author nacho
 */
class Language {

    const ENGLISH = 'en';
    const FRENCH = 'fr';
    const GERMAN = 'de';
    const ITALIAN = 'it';
    const NORWEGIAN = 'no';
    const RUSSIAN = 'ru';
    const SPANISH = 'es';

    /**
     * 
     */
    public static function all() {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return array_values($constants);
    }

}
