<?php

namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author administrador
 */
interface LanguageDetector {


    /**
     * 
     * @param string $text
     * @return string
     */
    public function detectLanguage(string $text): string;
}
