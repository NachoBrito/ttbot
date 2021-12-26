<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\Bus\Command\Command;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Common\Infraestructure\Symfony\SymfonyCommandBus;

/**
 * 
 *
 * @author nacho
 */
class LoggingCommandBus implements CommandBus{
    
    /**
     * 
     * @var CommandBus
     */
    private $bus;
    
    /**
     * 
     * @var Logger
     */
    private $logger;
    
    /**
     * 
     * @param CommandBus $bus
     * @param Logger $logger
     */
    public function __construct(CommandBus $bus, Logger $logger) {
        $this->bus = $bus;
        $this->logger = $logger;
    }

    /**
     * 
     * @param Command $command
     * @return void
     */
    public function dispatch(Command $command): void {
        $t0 = microtime(TRUE);
        
        $class = get_class($command);        
        $this->logger->info("Executting $class");
        
        $this->bus->dispatch($command);
        
        $t1 = microtime(TRUE);
        $dt = round(1000 * ($t1 - $t0));
        $this->logger->info("$class executed in $dt ms.");
    }

}
