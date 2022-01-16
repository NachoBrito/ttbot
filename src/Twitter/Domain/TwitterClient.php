<?php


namespace NachoBrito\TTBot\Twitter\Domain;

use stdClass;

/**
 *
 * @author nacho
 */
interface TwitterClient {
    

    /**
     * 
     * @return mixed
     */
    public function getMentions();
    

    /**
     * 
     * @param string $id
     * @param array $options
     * @return mixed
     */
    public function getTweet(string $id, array $options);
    
    

    /**
     * 
     * @param string $text
     * @param string $reply_to_id
     * @return string the new tweet id
     */
    public function tweet(string $text, string $reply_to_id = NULL): string;
}
