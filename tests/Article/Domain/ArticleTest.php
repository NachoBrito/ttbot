<?php

namespace NachoBrito\TTBot\Article\Domain;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2021-12-19 at 09:31:08.
 */
class ArticleTest extends TestCase {

    /**
     * @var Article
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->object = new Article;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        
    }

    /**
     * @todo   Implement testGetUrl().
     */
    public function testGetUrl() {
        self::assertSame($this->object, $this->object->setUrl('url'));
        self::assertSame('url', $this->object->getUrl());
    }

    /**
     * @todo   Implement testGetTitle().
     */
    public function testGetTitle() {
        self::assertSame($this->object, $this->object->setTitle("title"));
        self::assertSame("title", $this->object->getTitle());
    }

    /**
     * @todo   Implement testGetText().
     */
    public function testGetText() {
        self::assertSame($this->object, $this->object->setText("text"));
        self::assertSame("text", $this->object->getText());
    }

    /**
     * @todo   Implement testGetMetadata().
     */
    public function testGetMetadata() {
        $o = ["meta" => "data"];
        self::assertSame($this->object, $this->object->setMetadata($o));
        self::assertSame($o, $this->object->getMetadata());
    }

    /**
     * @todo   Implement testGetMetadata().
     */
    public function testGetLanguage() {
        $o = "lang";
        self::assertSame($this->object, $this->object->setLanguage($o));
        self::assertSame($o, $this->object->getLanguage());
    }    
}
