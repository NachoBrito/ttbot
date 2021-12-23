<?php


namespace NachoBrito\TTBot\Twitter\Domain;

use stdClass;

/**
 *
 * @author administrador
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
}
