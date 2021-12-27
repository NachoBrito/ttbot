<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use DateTime;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Common\Infraestructure\BufferedLogger;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceivedEvent;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\Model\TweetReference;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;
use PHPUnit\Framework\TestCase;
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

    /**
     * 
     */
    public function testPostReplyThread() {
        putenv('TRHEADS_MAX_TWEET_LENGTH=140');
        $sentences = [
            "The Twitter API v2 doesn't yet support media uploads, so for the time being we are using the v1.1 media upload endpoint to upload an image and attach it to a tweet. We will match that functionality as it comes online in Twitter API v2"
        ];
        $expected = [
            ["The Twitter API v2 doesn't yet support media uploads, so for the time being we are using the v1.1 media upload endpoint to [...]\n1/2", "1474271385283354629"],
            ["[...] upload an image and attach it to a tweet. We will match that functionality as it comes online in Twitter API v2\n2/2", "1474271385283354629"]
        ];
        $this->testPostReplyThreadCase($sentences, 2, $expected);

        $sentences = [
            "Pequeña frase cortá"
        ];
        $expected = [
            ["Pequeña frase cortá", "1474271385283354629"],
        ];
        $this->testPostReplyThreadCase($sentences, 2, $expected);

        $sentences = [
            "a b c d e f g h i j k l m ñ o p q r s t u v w x y z a b c d e f g h i j k l m n o p q r s t u v w x y z a b c d e f g h i j k l m n o p q r"
        ];
        $expected = [
            ["a b c d e f g h i j k l m ñ o p q r s t u v w x y z a b c d e f g h i j k l m n o p q r s t u v w x y z a b c d e f g h i j k l m n o p q r", "1474271385283354629"],
        ];
        $this->testPostReplyThreadCase($sentences, 2, $expected);


        putenv('TWITTER_USERNAME=THE_TWITTER_USER');
        $sentences = [
            "Never mention my own username: @THE_TWITTER_USER"
        ];
        $expected = [
            ["Never mention my own username: {me}", "1474271385283354629"],
        ];
        $this->testPostReplyThreadCase($sentences, 2, $expected);
        putenv('TWITTER_USERNAME');
        putenv('TRHEADS_MAX_TWEET_LENGTH');
    }

    /**
     * 
     */
    private function testPostReplyThreadCase($sentences, $max_tweets, $expected) {
        $logger = new BufferedLogger();
        $storage = $this->getStorage();

        $client = $this
                ->getMockBuilder(TwitterClient::class)
                ->getMock();

        $count = count($expected);
        $responses = array_fill(0, $count, '1474271385283354629');
        $client
                ->expects($this->exactly($count))
                ->method('tweet')
                ->withConsecutive(...$expected)
                ->willReturnOnConsecutiveCalls(...$responses);


        $eventBus = $this
                ->getMockBuilder(EventBus::class)
                ->getMock();

        $service = new TwitterService($client, $storage, $logger, $eventBus);
        $now = new DateTime();
        $originalTweet = (new Tweet())
                ->setAuthorName("El Origen de PI")
                ->setAuthorUsername("ElOrigenDePI")
                ->setAuthorId("885142155580276736")
                ->setCreatedAt($now)
                ->setId("1474271385283354629")
                ->setLang("und")
                ->setText("https://t.co/orEyiFG6nR")
        ;

        $service->postReplyThread($originalTweet, $sentences, $max_tweets);
    }

}
