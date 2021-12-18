<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

/**
 *
 * @author nacho
 */
interface QueryResolver {

    /**
     * 
     * @param iterable $handlers
     */
    public function buildHandlersMap(iterable $handlers);


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
