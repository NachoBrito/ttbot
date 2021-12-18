<?php declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

/**
 *
 * @author nacho
 */
interface QueryResolver {

    /**
     * 
     * @param iterable<QueryHandler> $handlers
     * @return array<string,array<int, QueryHandler>>
     */
    public function buildHandlersMap(iterable $handlers): array;


    /**
     * 
     * @param Query $query
     * @return string
     */
    public function getHandlerClass(Query $query): string;

    /**
     * 
     * @param QueryHandler $handler
     * @return string
     */
    public function getQueryClass(QueryHandler $handler): string;
}
