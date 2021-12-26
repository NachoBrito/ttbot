<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use DateTime;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Common\Infraestructure\BufferedLogger;
use NachoBrito\TTBot\Common\Infraestructure\InMemoryStorage;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceived;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceivedEvent;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\Model\TweetReference;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;
use PHPUnit\Framework\TestCase;
use Serializable;
use function GuzzleHttp\json_decode;

/**
 * 
 *
 * @author nacho
 */
class TwitterServiceTest extends TestCase {

    /**
     * 
     */
    public function testFirstTimeREtrievesAllStoresLastId() {
        $logger = new BufferedLogger();

        $get_mentions_response = json_decode(file_get_contents(__DIR__ . '/get_mentions.json'));
        $get_tweet_1_response = json_decode(file_get_contents(__DIR__ . '/get_tweet_1.json'));
        $get_tweet_2_response = json_decode(file_get_contents(__DIR__ . '/get_tweet_2.json'));

        $options = [
            'expansions' => 'author_id,referenced_tweets.id,referenced_tweets.id.author_id',
            'tweet.fields' => 'author_id,created_at,lang,public_metrics,referenced_tweets',
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
                ->expects($this->exactly(2))
                ->method('getTweet')
                ->withConsecutive(
                        ["1474271740675121185", $options],
                        ["1473926946706894850", $options])
                ->willReturnOnConsecutiveCalls(
                        $get_tweet_1_response,
                        $get_tweet_2_response
        );

        $eventBus = $this
                ->getMockBuilder(EventBus::class)
                ->getMock();
        $eventBus
                ->expects($this->exactly(2))
                ->method('dispatch')
                ->with($this->isInstanceOf(TwitterMentionReceivedEvent::class));

        $service = new TwitterService($client, $storage, $logger, $eventBus);

        $expected = [];
        $created_at_1 = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-24T06:52:28.000Z");
        $created_at_2 = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-23T08:02:22.000Z");
        $created_at_3 = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-24T06:51:03.000Z");


        $ref_tweet = (new Tweet())
                ->setAuthorName("El Origen de PI")
                ->setAuthorUsername("ElOrigenDePI")
                ->setAuthorId("885142155580276736")
                ->setCreatedAt($created_at_3)
                ->setId("1474271385283354629")
                ->setLang("und")
                ->setText("https://t.co/orEyiFG6nR")
        ;
        $ref = (new TweetReference())
                ->setTweet($ref_tweet)
                ->setType('replied_to');

        $expected[] = $tweet = (new Tweet())
                ->setAuthorName("TheCleverClick")
                ->setAuthorUsername("TheCleverClick")
                ->setAuthorId("715508356128116736")
                ->setCreatedAt($created_at_1)
                ->setId("1474271740675121185")
                ->setLang("und")
                ->setText("@ElOrigenDePI @ElOrigenDePI")
                ->addReference($ref)
        ;
        $expected[] = $tweet = (new Tweet())
                ->setAuthorName("TheCleverClick")
                ->setAuthorUsername("TheCleverClick")
                ->setAuthorId("715508356128116736")
                ->setCreatedAt($created_at_2)
                ->setId("1473926946706894850")
                ->setLang("en")
                ->setText("I mention you, @ElOrigenDePI")
        ;

        self::assertEquals($expected, $service->getNewMentions());
        self::assertEquals('1474271740675121185', $storage->get(TwitterService::LAST_MENTION_ID));
    }

    /**
     * 
     */
    public function testHonorLastMentionId() {
        $logger = new BufferedLogger();

        $get_mentions_response = json_decode(file_get_contents(__DIR__ . '/get_mentions.json'));
        $get_tweet_1_response = json_decode(file_get_contents(__DIR__ . '/get_tweet_1.json'));

        $options = [
            'expansions' => 'author_id,referenced_tweets.id,referenced_tweets.id.author_id',
            'tweet.fields' => 'author_id,created_at,lang,public_metrics,referenced_tweets',
            'user.fields' => 'name'
        ];

        $storage = $this->getStorage();
        $storage->set(TwitterService::LAST_MENTION_ID, '1473926946706894850');


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
                ->with("1474271740675121185", $options)
                ->willReturn($get_tweet_1_response)
        ;

        $eventBus = $this
                ->getMockBuilder(EventBus::class)
                ->getMock();
        $eventBus
                ->expects($this->once())
                ->method('dispatch')
                ->with($this->isInstanceOf(TwitterMentionReceivedEvent::class));

        $service = new TwitterService($client, $storage, $logger, $eventBus);

        $expected = [];
        $created_at_1 = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-24T06:52:28.000Z");
        $created_at_3 = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, "2021-12-24T06:51:03.000Z");


        $ref_tweet = (new Tweet())
                ->setAuthorName("El Origen de PI")
                ->setAuthorUsername("ElOrigenDePI")
                ->setAuthorId("885142155580276736")
                ->setCreatedAt($created_at_3)
                ->setId("1474271385283354629")
                ->setLang("und")
                ->setText("https://t.co/orEyiFG6nR")
        ;
        $ref = (new TweetReference())
                ->setTweet($ref_tweet)
                ->setType('replied_to');

        $expected[] = $tweet = (new Tweet())
                ->setAuthorName("TheCleverClick")
                ->setAuthorUsername("TheCleverClick")
                ->setAuthorId("715508356128116736")
                ->setCreatedAt($created_at_1)
                ->setId("1474271740675121185")
                ->setLang("und")
                ->setText("@ElOrigenDePI @ElOrigenDePI")
                ->addReference($ref)
        ;

        self::assertEquals($expected, $service->getNewMentions());
        self::assertEquals('1474271740675121185', $storage->get(TwitterService::LAST_MENTION_ID));
    }

    /**
     * 
     * @return #anon#TwitterServiceTest_php#1
     */
    private function getStorage() {
        $storage = new class() implements Storage {

            private $storage = [];

            public function get(string $key, string $default = NULL): ?string {
                return isset($this->storage[$key]) ? $this->storage[$key] : $default;
            }

            public function set(string $key, string $value): void {
                $this->storage[$key] = $value;
            }

            public function getStorage() {
                return $this->storage;
            }
        };
        return $storage;
    }

}
