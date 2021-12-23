<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use DateTime;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Common\Infraestructure\BufferedLogger;
use NachoBrito\TTBot\Common\Infraestructure\InMemoryStorage;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;
use PHPUnit\Framework\TestCase;
use Serializable;

/**
 * 
 *
 * @author nacho
 */
class TwitterServiceTest extends TestCase {

    public function testGetMentions() {
        $logger = new BufferedLogger();

        $get_mentions_response = json_decode(file_get_contents(__DIR__ . '/get_mentions.json'));
        $get_tweet_response = json_decode(file_get_contents(__DIR__ . '/get_tweet.json'));
        $options = [
            'expansions' => 'author_id',
            'tweet.fields' => 'author_id,created_at,lang,public_metrics',
            'user.fields' => 'name'
        ];

        $storage = $this->getStorage();

        $client = $this
                ->getMockBuilder(TwitterClient::class)
                ->getMock();

        $client
                ->expects($this->once())
                ->method('getMentions')
                ->willReturn($get_mentions_response);

        $client
                ->expects($this->once())
                ->method('getTweet')
                ->with('1473926946706894850', $options)
                ->willReturn($get_tweet_response);

        $service = new TwitterService($client, $storage, $logger);


        $expected = [];
        $created_at = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-23T08:02:22.000Z");
        $expected[] = $tweet = (new Tweet())
                ->setAuthorName("TheCleverClick")
                ->setAuthorUsername("TheCleverClick")
                ->setAuthorId("715508356128116736")
                ->setCreatedAt($created_at)
                ->setId("1473926946706894850")
                ->setLang("en")
                ->setText("I mention you, @ElOrigenDePI")
        ;

        self::assertEquals($expected, $service->getNewMentions());
    }

    /**
     * 
     * @return #anon#TwitterServiceTest_php#1
     */
    private function getStorage() {
        $storage = new class() implements Storage {

            private $storage = [];

            public function get(string $key): Serializable {
                return $this->storage[$key];
            }

            public function set(string $key, Serializable $value): void {
                $this->storage[$key] = $value;
            }

            public function getStorage() {
                return $this->storage;
            }
        };
        return $storage;
    }

}
