<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\ConfigLoader;

/**
 * 
 *
 * @author nacho
 */
class INIConfigLoader implements ConfigLoader {
    private const GLOBAL_FILE = '.env';
    private const LOCAL_FILE = '.env.local';
    
    /**
     * 
     * @var string
     */
    private $basedir;
    
    /**
     * 
     * @param string $basedir
     */
    public function __construct(string $basedir) {
        $this->basedir = $basedir;
    }

    public function load(): void {
        $this->loadFile(self::GLOBAL_FILE, TRUE);
        $this->loadFile(self::LOCAL_FILE, FALSE);
    }

    /**
     * 
     * @param type $filename
     * @param type $fail_if_not_found
     * @return type
     * @throws RuntimeException
     */
    private function loadFile($filename, $fail_if_not_found = FALSE) {
        
        $path = realpath($this->basedir . "/$filename");
        
        if (!is_file($path)) {
            if ($fail_if_not_found) {
                throw new RuntimeException("Configuration file not found at $path");
            }
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            if (!$line || $line[0] === '#') {
                continue;
            }

            putenv($line);
        }
    }

}
