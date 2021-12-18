<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

/**
 *
 * @author nacho
 */
interface QueryBus {

    public function ask(Query $query): ?Result;
}
