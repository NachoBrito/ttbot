<?php declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Command;

/**
 * Description of CommandResolver
 *
 * @author nacho
 */
class NameCommandResolver implements CommandResolver {

    
    /**
     * 
     * @param Command $command
     */
    public function getHandlerClass(Command $command): string {
        $command_class = get_class($command);

        $this->validateCommandClass($command_class);

        $handler_class = preg_replace('/Command$/', 'Handler', $command_class);

        return $handler_class;
    }


    /**
     * 
     * @param string $command_class
     * @return void
     * @throws InvalidCommandException
     */
    private function validateCommandClass(string $command_class):void {
        if (!preg_match('/Command$/', $command_class)) {
            throw new InvalidCommandException("Class $command_class is not a valid command class name. Suffix 'Command' is mandatory.");
        }
    }
    

    /**
     * 
     * @param string $handler_class
     * @return void
     * @throws InvalidHandlerException
     */
    private function validateHandlerClass(string $handler_class):void {
        if (!preg_match('/Handler$/', $handler_class)) {
            throw new InvalidHandlerException("Class $handler_class is not a valid command handler class name. Suffix 'Handler' is mandatory.");
        }
    }

    /**
     * 
     * @param CommandHandler $handler
     * @return string
     */
    public function getCommandClass(CommandHandler $handler): string {
        $handler_class = get_class($handler);

        $this->validateHandlerClass($handler_class);

        $command_class = preg_replace('/Handler$/', 'Command', $handler_class);

        return $command_class;        
    }
    /**
     * 
     * @param iterable<CommandHandler> $handlers
     * @return array<string,array<int, CommandHandler>>
     */
    public function buildHandlersMap(iterable $handlers):array {
        $map = [];
        foreach($handlers as $handler)
        {
            $command_class = $this->getCommandClass($handler);
            
            $map[$command_class] = [$handler];
        }
        return $map;
    }


}
