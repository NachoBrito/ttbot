<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Model;

use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class HTTPResponseTest extends TestCase{
    public function testGetters()
    {
        $headers = ['header' => 'value'];
        $content = 'content';
        
        $response = new HTTPResponse($headers, $content);
        
        self::assertSame($headers, $response->getHeaders());
        self::assertSame($content, $response->getContent());
    }
}
