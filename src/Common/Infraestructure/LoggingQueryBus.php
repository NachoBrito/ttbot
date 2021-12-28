<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\Bus\Query\Query;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryBus;
use NachoBrito\TTBot\Common\Domain\Bus\Query\Result;
use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 *
 * @author nacho
 */
class LoggingQueryBus implements QueryBus {

    /**
     * 
     * @var QueryBus
     */
    private $bus;

    /**
     * 
     * @var Logger
     */
    private $logger;

    /**
     * 
     * @param QueryBus $bus
     * @param Logger $logger
     */
    public function __construct(QueryBus $bus, Logger $logger) {
        $this->bus = $bus;
        $this->logger = $logger;
    }

    /**
     * 
     * @param Query $query
     * @return Result|null
     */
    public function ask(Query $query): ?Result {
        $t0 = microtime();

        $class = get_class($query);
        $this->logger->info("Executting $class");

        $result = $this->bus->ask($query);

        $t1 = microtime();
        $dt = 1000 * ($t1 - $t0);
        $this->logger->info("$class executed in $dt ms.");
        
        return $result;
    }

}
