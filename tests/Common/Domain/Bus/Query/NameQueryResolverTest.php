<?php
declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

use PHPUnit\Framework\TestCase;

/**
 * Description of NameQueryResolverTest
 *
 * @author nacho
 */
class NameQueryResolverTest extends TestCase{
    /**
     * 
     */
    public function testQueryClassNameResolution():void
    {
        $resolver = new NameQueryResolver();
        
        $cmd1 = new DummyQuery();
        $cmd2 = new QueryDummyQuery();
        $cmd3 = new DummyQueryInvalid();
        
        $result1 = $resolver->getHandlerClass($cmd1);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Query\DummyHandler', $result1);
        
        $result2 = $resolver->getHandlerClass($cmd2);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Query\QueryDummyHandler', $result2);
        
        $this->expectException(InvalidQueryException::class);   
        $resolver->getHandlerClass($cmd3);
    }
    
    /**
     * 
     */
    public function testHandlerClassNameResolution():void
    {
        $resolver = new NameQueryResolver();
        
        $cmd1 = new DummyHandler();
        $cmd2 = new HandlerDummyHandler();
        $cmd3 = new DummyHandlerInvalid();
        
        $result1 = $resolver->getQueryClass($cmd1);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Query\DummyQuery', $result1);
        
        $result2 = $resolver->getQueryClass($cmd2);
        self::assertSame('NachoBrito\TTBot\Common\Domain\Bus\Query\HandlerDummyQuery', $result2);
        
        $this->expectException(InvalidHandlerException::class);   
        $resolver->getQueryClass($cmd3);
    }
    
    /**
     * 
     */
    public function testGetHandlerMap():void
    {
        $resolver = new NameQueryResolver();
        $handlers = [
            new DummyHandler(),
            new HandlerDummyHandler()
        ];
        $map = $resolver->buildHandlersMap($handlers);
        
        $num = count($map);
        self::assertSame(2, $num);
        
        self::assertSame($handlers[0], $map[DummyQuery::class][0]);
        self::assertSame($handlers[1], $map['NachoBrito\TTBot\Common\Domain\Bus\Query\HandlerDummyQuery'][0]);
    }
}
class DummyQuery implements Query{
    
}
class QueryDummyQuery implements Query{
    
}
class DummyQueryInvalid implements Query{
    
}

class DummyHandler implements QueryHandler{
    
}
class HandlerDummyHandler implements QueryHandler{
    
}
class DummyHandlerInvalid implements QueryHandler{
    
}