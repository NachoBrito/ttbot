<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use GuzzleHttp\Client;
use NachoBrito\TTBot\Common\Domain\HTTPClient;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
use NachoBrito\TTBot\Common\Domain\Model\HTTPResponse;

/**
 * 
 *
 * @author nacho
 */
class GuzzleHTTPClient implements HTTPClient {

    /**
     * 
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * 
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function get(string $url, array $headers = []): HTTPResponse {
        $client = new Client([]);
        $options = [
            'headers' => $headers
        ];
        $this->logger->info("GET $url");
        $response = $client->get($url, $options);
        $response_headers = $response->getHeaders();
        $content = $response->getBody()->getContents();
        return new HTTPResponse($response_headers, $content);
    }

}
