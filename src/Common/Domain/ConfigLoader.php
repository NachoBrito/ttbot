<?php


namespace NachoBrito\TTBot\Common\Domain;

/**
 *
 * @author nacho
 */
interface ConfigLoader {
    
    /**
     * 
     * @return void
     */
    public function load(): void;
}
