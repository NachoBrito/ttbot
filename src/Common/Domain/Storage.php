<?php

namespace NachoBrito\TTBot\Common\Domain;

use Serializable;

/**
 *
 * @author nacho 
 */
interface Storage {

    public function set(string $key, string $value): void;

    public function get(string $key, string $default = NULL): ?string;
    
    public function delete(string $key): void;
}
