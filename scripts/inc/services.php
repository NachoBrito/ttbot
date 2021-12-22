<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandHandler;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryBus;
use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryHandler;
use NachoBrito\TTBot\Common\Infraestructure\Symfony\SymfonyCommandBus;
use NachoBrito\TTBot\Common\Infraestructure\Symfony\SymfonyEventBus;
use NachoBrito\TTBot\Common\Infraestructure\Symfony\SymfonyQueryBus;
use Symfony\Component\DependencyInjection\Reference;

return function (ContainerConfigurator $configurator) {
    // default configuration for services in *this* file
    $services = $configurator->services()
            ->defaults()
            ->public()            
            ->autowire()      // Automatically injects dependencies in your services.
            ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
    ;
    $services
            ->instanceof(EventSubscriber::class)            
            ->tag('nachobrito.ttbot.eventsubscriber');
    $services
            ->instanceof(CommandHandler::class)            
            ->tag('nachobrito.ttbot.commandhandler');
    $services
            ->instanceof(QueryHandler::class)            
            ->tag('nachobrito.ttbot.queryhandler');

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services->load('NachoBrito\\TTBot\\', __DIR__ . '/../../src/*')
            ->exclude([
                __DIR__ . '/../../src/{DependencyInjection,Entity,Tests,Kernel.php}',
                __DIR__ . '/../../src/**/Model/*.php',
                __DIR__ . '/../../src/**/*Event.php',
                __DIR__ . '/../../src/**/*Exception.php',
                __DIR__ . '/../../src/**/*Command.php',
                __DIR__ . '/../../src/**/*Query.php',
                __DIR__ . '/../../src/Common/Infraestructure/Symfony/SymfonyCommandBus.php',
                __DIR__ . '/../../src/Common/Infraestructure/Symfony/SymfonyEventBus.php',
                __DIR__ . '/../../src/Common/Infraestructure/Symfony/SymfonyQueryBus.php',
    ]);
    
    /*
     * EVENT BUS CONFIGURATION
     */    
    $services->set(EventBus::class, SymfonyEventBus::class)
            ->args([
                tagged_iterator('nachobrito.ttbot.eventsubscriber'),
                service(EventResolver::class)
            ]);
    /*
     * COMMAND BUS CONFIGURATION
     */
    $services->set(CommandBus::class, SymfonyCommandBus::class)
            ->args([
                tagged_iterator('nachobrito.ttbot.commandhandler'),
                service(CommandResolver::class)
            ]);
    
    /*
     * QUERY BUS CONFIGURATION
     */    
    $services->set(QueryBus::class, SymfonyQueryBus::class)
            ->args([
                tagged_iterator('nachobrito.ttbot.queryhandler'),
                service(CommandResolver::class)
            ]);
    
    
};

