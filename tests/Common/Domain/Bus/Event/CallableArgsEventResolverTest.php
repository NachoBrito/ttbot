<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Command;

use NachoBrito\TTBot\Common\Domain\Bus\Event\CallableArgsEventResolver;
use NachoBrito\TTBot\Common\Domain\Bus\Event\Event;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use PHPUnit\Framework\TestCase;


/**
 * Description of CallableArgsEventResolverTest
 *
 * @author nacho
 */
class CallableArgsEventResolverTest extends TestCase {

    public function testResolve() {
        $resolver = new CallableArgsEventResolver();
        $list = [
            new DummySubscriber1(),
            new DummySubscriber2(),
        ];
        $expected = [
            DummyEvent1::class => [
                DummySubscriber1::class,
                DummySubscriber2::class,
            ]
        ];
        $map = $resolver->buildSubscribersMap($list);
        
        self::assertSame($expected, $map);
    }

}

class DummyEvent1 implements Event {
    
}

class DummySubscriber1 implements EventSubscriber {

    public function __invoke(DummyEvent1 $evt) {
        
    }

}

class DummySubscriber2 implements EventSubscriber {

    public function __invoke(DummyEvent1 $evt) {
        
    }

}
