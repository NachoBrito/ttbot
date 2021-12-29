<?php

namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author administrador
 */
interface RateLimiter {
    
    /**
     * 
     * @param string $clientId
     * @param string $actionId
     * @return bool
     */
    public function actionAllowed(string $clientId, string $actionId): bool;
}
