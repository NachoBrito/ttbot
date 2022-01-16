<?php

namespace NachoBrito\TTBot\Common\Domain;

use NachoBrito\TTBot\Common\Domain\Model\HTTPResponse;

/**
 *
 * @author nacho
 */
interface HTTPClient {

    /**
     * 
     * @param string $url
     * @return string
     */
    public function get(string $url, array $headers = []): HTTPResponse;
}
