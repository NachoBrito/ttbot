<?php

namespace NachoBrito\TTBot\Common\Domain;

use Serializable;

/**
 *
 * @author administrador
 */
interface Storage {

    public function set(string $key, Serializable $value): void;

    public function get(string $key, Serializable $default = NULL): ?Serializable;
}
