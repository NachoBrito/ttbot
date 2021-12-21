<?php

namespace NachoBrito\TTBot\Common\Infraestructure\Symfony;

use NachoBrito\TTBot\Common\Domain\Bus\Command\Command;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandResolver;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

/**
 * Description of SymfonyCommandBus
 *
 * @author nacho
 */
class SymfonyCommandBus implements CommandBus {

    /**
     * 
     * @var MessageBus
     */
    private $bus;


    
    /**
     * 
     * @param iterable $handlers
     * @param CommandResolver $resolver
     */
    public function __construct(iterable $handlers, CommandResolver $resolver) {

        $handlers_map = $resolver->buildHandlersMap($handlers);
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator($handlers_map)),
        ]);
    }

    public function dispatch(Command $command): void {
        $this->bus->dispatch($command);
    }

}
