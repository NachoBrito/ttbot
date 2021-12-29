<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure\Symfony;

use NachoBrito\TTBot\Common\Domain\Storage;
use Symfony\Component\RateLimiter\LimiterStateInterface;
use Symfony\Component\RateLimiter\Storage\StorageInterface;

/**
 * 
 *
 * @author nacho
 */
class SymfonyRateLimiterStorage implements StorageInterface {

    /**
     * 
     * @var Storage
     */
    private $storage;

    /**
     * 
     * @param Storage $storage
     */
    public function __construct(Storage $storage) {
        $this->storage = $storage;
    }

    public function save(LimiterStateInterface $limiterState): void {

        if (null !== ($expireSeconds = $limiterState->getExpirationTime())) {
            $expireAt = microtime(true) + $expireSeconds;
        } else {
            $expireAt = -1;
        }

        $o = [
            'expireAt' => $expireAt,
            'state' => serialize($limiterState)
        ];

        $key = $this->getKey($limiterState->getId());
        $this->storage->set($key, json_encode($o));
    }

    public function fetch(string $limiterStateId): ?LimiterStateInterface {
        $key = $this->getKey($limiterStateId);
        $json = $this->storage->get($key);
        if (!$json) {
            return NULL;
        }
        $o = json_decode($json, TRUE);
        $expireAt = $o['expireAt'];
        $serialized = $o['state'];
        if (null !== $expireAt && $expireAt <= microtime(true)) {
            $this->storage->delete($key);
            return NULL;
        }
        return unserialize($serialized);
    }

    public function delete(string $limiterStateId): void {
        $key = $this->getKey($limiterStateId);
        $this->storage->delete($key);
    }

    private function getKey(string $limiterStateId): string {
        return 'rate-limiter-' . md5($limiterStateId);
    }

}
