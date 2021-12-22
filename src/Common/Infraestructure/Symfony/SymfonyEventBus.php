<?php

namespace NachoBrito\TTBot\Common\Infraestructure\Symfony;

use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Event\Event;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

/**
 * Description of SymfonyCommandBus
 *
 * @author nacho
 */
class SymfonyEventBus implements EventBus {

    /**
     * 
     * @var MessageBus
     */
    private $bus;


    
    /**
     * 
     * @param iterable<EventSubscriber> $handlers
     * @param CommandResolver $resolver
     */
    public function __construct(iterable $subscribers, EventResolver $resolver) {

        $handlers_map = $resolver->buildSubscribersMap($subscribers);
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator($handlers_map)),
        ]);
    }

    public function dispatch(Event $event): void {
        $this->bus->dispatch($event);
    }

}
