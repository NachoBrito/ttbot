<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Twitter\Application;

use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryHandler;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryResult;



/**
 * 
 *
 * @author nacho
 */
class MentionsHandler implements QueryHandler {
    
    /**
     * 
     * @var TwitterService
     * 
     */
    private $twitter;

    public function __construct(TwitterService $twitter) {
        $this->twitter = $twitter;
    }

        /**
     * 
     */
    public function __invoke(MentionsQuery $query) {
        $mentions = $this->twitter->getNewMentions();
        return new QueryResult($query, $mentions);
    }

}
