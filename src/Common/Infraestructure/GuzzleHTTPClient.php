<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use GuzzleHttp\Client;
use NachoBrito\TTBot\Common\Domain\HTTPClient;

/**
 * 
 *
 * @author nacho
 */
class GuzzleHTTPClient implements HTTPClient {

    /**
     * 
     * @param string $url
     * @return string
     */
    public function get(string $url, array $headers = []): string {
        $client = new Client([]);
        $options = [
            'headers' => $headers
        ];
        $response = $client->get($url, $options)->getBody();
        return $response->getContents();
    }

}
