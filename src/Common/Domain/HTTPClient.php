<?php

namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author administrador
 */
interface HTTPClient {

    /**
     * 
     * @param string $url
     * @return string
     */
    public function get(string $url, array $headers = []): string;
}
