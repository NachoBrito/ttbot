<?php
declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Command;

use PHPUnit\Framework\TestCase;

/**
 * Description of NameCommandResolverTest
 *
 * @author nacho
 */
class NameCommandResolverTest extends TestCase{
    /**
     * 
     */
    public function testCommandClassNameResolution():void
    {
        $resolver = new NameCommandResolver();
        
        $cmd1 = new DummyCommand();
        $cmd2 = new CommandDummyCommand();
        $cmd3 = new DummyCommandInvalid();
        
        $result1 = $resolver->getHandlerClass($cmd1);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Command\DummyHandler', $result1);
        
        $result2 = $resolver->getHandlerClass($cmd2);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Command\CommandDummyHandler', $result2);
        
        $this->expectException(InvalidCommandException::class);   
        $resolver->getHandlerClass($cmd3);
    }
    
    /**
     * 
     */
    public function testHandlerClassNameResolution():void
    {
        $resolver = new NameCommandResolver();
        
        $cmd1 = new DummyHandler();
        $cmd2 = new HandlerDummyHandler();
        $cmd3 = new DummyHandlerInvalid();
        
        $result1 = $resolver->getCommandClass($cmd1);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Command\DummyCommand', $result1);
        
        $result2 = $resolver->getCommandClass($cmd2);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Command\HandlerDummyCommand', $result2);
        
        $this->expectException(InvalidHandlerException::class);   
        $resolver->getCommandClass($cmd3);
    }
    
    /**
     * 
     */
    public function testGetHandlerMap():void
    {
        $resolver = new NameCommandResolver();
        $handlers = [
            new DummyHandler(),
            new HandlerDummyHandler()
        ];
        $map = $resolver->buildHandlersMap($handlers);
        
        $num = count($map);
        self::assertSame(2, $num);
        
        self::assertSame($handlers[0], $map[DummyCommand::class][0]);
        self::assertSame($handlers[1], $map['NachoBrito\TTBot\Common\Domain\Bus\Command\HandlerDummyCommand'][0]);
    }
}
class DummyCommand implements Command{
    
}
class CommandDummyCommand implements Command{
    
}
class DummyCommandInvalid implements Command{
    
}

class DummyHandler implements CommandHandler{
    
}
class HandlerDummyHandler implements CommandHandler{
    
}
class DummyHandlerInvalid implements CommandHandler{
    
}