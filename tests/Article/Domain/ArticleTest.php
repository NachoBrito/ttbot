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
     * @covers NachoBrito\TTBot\Article\Domain\Article::getUrl
     * @todo   Implement testGetUrl().
     */
    public function testGetUrl() {
        $this->object->setUrl('url');
        self::assertSame('url', $this->object->getUrl());
    }

    /**
     * @covers NachoBrito\TTBot\Article\Domain\Article::getTitle
     * @todo   Implement testGetTitle().
     */
    public function testGetTitle() {
        $this->object->setTitle("title");
        self::assertSame("title", $this->object->getTitle());
    }

    /**
     * @covers NachoBrito\TTBot\Article\Domain\Article::getText
     * @todo   Implement testGetText().
     */
    public function testGetText() {
        $this->object->setText("text");
        self::assertSame("text", $this->object->getText());
    }

    /**
     * @covers NachoBrito\TTBot\Article\Domain\Article::getMetadata
     * @todo   Implement testGetMetadata().
     */
    public function testGetMetadata() {
        $o = ["meta" => "data"];
        $this->object->setMetadata($o);
        self::assertSame($o, $this->object->getMetadata());
    }

}
