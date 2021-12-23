<?php


namespace NachoBrito\TTBot\Twitter\Domain;

/**
 *
 * @author administrador
 */
interface TwitterClient {
    
    /**
     * 
     */
    public function getNewMentions();
}
