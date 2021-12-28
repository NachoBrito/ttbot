<?php

declare( strict_types=1 );



namespace NachoBrito\TTBot\Common\Domain;

/**
 * 
 *
 * @author nacho
 */
class UserAgentsHelper {
    
    public static function getUserAgent()
    {
        return "Mozilla/5.0 (compatible; TTBot; +https://github.com/NachoBrito/ttbot)";
    }
}
