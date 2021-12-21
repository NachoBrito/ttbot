<?php

namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author administrador
 */
interface HTMLTextExtractor {
    /**
     * 
     * @param string $html
     * @return string
     */
    public function extractText(string $html):string;
}
