<?php

namespace Mohamed179\Core;

class Session
{
    private const FLASH_KEY = 'flash_key';
    private const FLASH_TO_BE_REMOVED = 'flash_to_be_removed';
    private const FLASH_VALUE = 'flash_value';

    public function __construct()
    {
        session_start();
        
        // Marking the flash messages came from previous request as to be removed
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach(array_keys($flashMessages) as $key) {
            $_SESSION[self::FLASH_KEY][$key][self::FLASH_TO_BE_REMOVED] = true;
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function unset(string $key)
    {
        unset($_SESSION[$key]);
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function hasFlash(string $key): bool
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        return array_key_exists($key, $flashMessages);
    }

    public function setFlash(string $key, string $value)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            self::FLASH_TO_BE_REMOVED => false,
            self::FLASH_VALUE => $value
        ];
    }

    public function unsetFlash(string $key)
    {
        unset($_SESSION[self::FLASH_KEY][$key]);
    }

    public function getFlash(string $key)
    {
        return $_SESSION[self::FLASH_KEY][$key][self::FLASH_VALUE] ?? null;
    }

    public function __destruct()
    {
        // Removing the to be removed flash messages
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach(array_keys($flashMessages) as $key) {
            if($_SESSION[self::FLASH_KEY][$key][self::FLASH_TO_BE_REMOVED]) {
                $this->unsetFlash($key);
            }
        }
    }
}