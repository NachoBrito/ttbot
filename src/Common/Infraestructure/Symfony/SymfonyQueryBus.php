<?php

namespace NachoBrito\TTBot\Common\Infraestructure\Symfony;

use NachoBrito\TTBot\Common\Domain\Bus\Query\Query;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryBus;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Query\Result;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Description of SymfonyQueryBus
 *
 * @author nacho
 */
class SymfonyQueryBus implements QueryBus {

    /**
     * 
     * @var MessageBus
     */
    private $bus;


    

    /**
     * 
     * @param iterable $handlers
     * @param QueryResolver $resolver
     */
    public function __construct(iterable $handlers, QueryResolver $resolver) {

        $handlers_map = $resolver->buildHandlersMap($handlers);
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator($handlers_map)),
        ]);
    }

    /**
     * 
     * @param Query $query
     * @return Result|null
     */
    public function ask(Query $query): ?Result {
        /** @var HandledStamp $stamp */
        $stamp = $this->bus->dispatch($query)->last(HandledStamp::class);
        return $stamp->getResult();
    }

}
