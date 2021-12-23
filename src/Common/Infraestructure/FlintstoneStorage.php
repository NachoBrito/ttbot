<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use Flintstone\Flintstone;
use NachoBrito\TTBot\Common\Domain\Storage;
use Serializable;

/**
 * 
 *
 * @author nacho
 */
class FlintstoneStorage implements Storage {

    /**
     * 
     * @var Flintstone
     */
    private $db;

    /**
     * 
     * @return Flintstone
     */
    private function getDB(): Flintstone {
        if (!$this->db) {
            $path = getenv('STORAGE_DIR');

            $this->db = new Flintstone('storage', [
                'dir' => $path,
                'cache' => TRUE,
            ]);
        }
        return $this->db;
    }

    //put your code here
    public function get(string $key, Serializable $default = NULL): ?Serializable {
        $o = $this->getDB()->get($key);
        
        return $o ?? $default;
    }

    /**
     * 
     * @param string $key
     * @param Serializable $value
     * @return void
     */
    public function set(string $key, Serializable $value): void {
        $this->getDB()->set($key, $value);
        $this->getDB()->flush();
    }

}
