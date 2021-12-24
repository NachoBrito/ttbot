<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Domain;

use DateTime;
use NachoBrito\TTBot\Common\Domain\Error\DomainException;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\Model\TweetReference;
use stdClass;

/**
 * 
 *
 * @author nacho
 */
class TweetFactory {

    /**
     * 
     * @param stdClass $o
     * @return Tweet
     */
    public static function fromAPIResponse(stdClass $o): Tweet {
        self::validateInput($o);

        $user_map = [];
        array_walk($o->includes->users, function($u) use(&$user_map) {
            $user_map[$u->id] = $u;
        });

        $user = $user_map[$o->data->author_id];
        $created_at = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, $o->data->created_at);
        $tweet = (new Tweet())
                ->setAuthorName($user->name)
                ->setAuthorUsername($user->username)
                ->setAuthorId($o->data->author_id)
                ->setCreatedAt($created_at)
                ->setId($o->data->id)
                ->setLang($o->data->lang)
                ->setText($o->data->text)
        ;

        self::loadReferences($o, $tweet, $user_map);

        return $tweet;
    }

    /**
     * 
     * @param stdClass $o
     * @throws DomainException
     */
    private static function validateInput(stdClass $o): void {
        if (
                !isset($o->data) ||
                !isset($o->includes) ||
                !isset($o->includes->users)
        ) {
            throw new DomainException("API Response is not valid! " . json_encode($o));
        }

        if (
                isset($o->data->referenced_tweets) &&
                !isset($o->includes->tweets)
        ) {
            throw new DomainException("API Response is not valid! " . json_encode($o));
        }
    }

    /**
     * 
     * @param \stdClass $o
     * @param Tweet $tweet
     * @return void
     */
    private static function loadReferences(\stdClass $o, Tweet $tweet, array $user_map): void {
        if (!isset($o->data->referenced_tweets)) {
            return;
        }

        $tweets_map = [];
        array_walk($o->includes->tweets, function($u) use(&$tweets_map) {
            $tweets_map[$u->id] = $u;
        }); 
        
        foreach($o->data->referenced_tweets as $ref)
        {
            $reference = new TweetReference();
            $reference->setType($ref->type);
            $tweet_object = $tweets_map[$ref->id];
            
            $created_at = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, $tweet_object->created_at);
            $author = $user_map[$tweet_object->author_id];
            $referenced_tweet = (new Tweet())
                    ->setAuthorId($author->id)
                    ->setAuthorName($author->name)
                    ->setAuthorUsername($author->username)
                    ->setCreatedAt($created_at)
                    ->setId($tweet_object->id)
                    ->setLang($tweet_object->lang)
                    ->setText($tweet_object->text)
                    ;
            
            $reference->setTweet($referenced_tweet);
            
            $tweet->addReference($reference);            
        }
    }

}
