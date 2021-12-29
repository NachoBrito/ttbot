<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure\Symfony;

use NachoBrito\TTBot\Common\Domain\RateLimiter;
use NachoBrito\TTBot\Common\Domain\Storage;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\LimiterStateInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\StorageInterface;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class SymfonyRateLimiter implements RateLimiter {

    /**
     * 
     * @var array<LimiterInterface>
     */
    private $limiters = [];
    
    /**
     * 
     * @var StorageInterface
     */
    private $storage;

    /**
     * 
     * @param Storage $storage
     */
    public function __construct(Storage $storage) {
        $this->storage = new SymfonyRateLimiterStorage($storage);
    }

    /**
     * 
     * @param string $actionId
     * @return RateLimiterFactory
     */
    private function getLimiterFactory(string $actionId) {
        if (!isset($this->limiters[$actionId])) {
            $factory = new RateLimiterFactory([
                'id' => $actionId,
                'policy' => 'token_bucket',
                'limit' => 10,
                'rate' => ['interval' => '24 hour'],
                    ], $this->storage);
            $this->limiters[$actionId] = $factory;
        }
        return $this->limiters[$actionId];
    }

    /**
     * 
     * @param string $clientId
     * @param string $actionId
     * @return bool
     */
    public function actionAllowed(string $clientId, string $actionId): bool {
        $limiter = $this
                ->getLimiterFactory($actionId)
                ->create($clientId);
        
        return $limiter->consume()->isAccepted();
    }


}
