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
        $s1 = new DummySubscriber1();
        $s2 = new DummySubscriber2();
        
        $list = [
            $s1,
            $s2,
        ];
        $expected = [
            DummyEvent1::class => [
                $s1,
                $s2
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
