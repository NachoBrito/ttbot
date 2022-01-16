<?php

namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author nacho
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
