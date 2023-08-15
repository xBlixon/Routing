<?php

namespace Velsym\Routing\Communication;

class Session
{
    private static ?Session $instance = NULL;

    private function __construct()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
    }

    public static function getInstance(): self
    {
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function set(string $key, mixed $value): self
    {
        $_SESSION[$key] = serialize($value);
        return $this;
    }

    public function get(string $key): mixed
    {
        return unserialize($_SESSION[$key] ?? "") ?? NULL;
    }

    public function setFlash(mixed $value): self
    {
        return $this->set('flash', $value);
    }

    public function getFlash(): mixed
    {
        $flash = $this->get('flash');
        $this->purge('flash');
        return $flash;
    }

    public function purge(string $key): self
    {
        unset($_SESSION[$key]);
        return $this;
    }

    public function close(): void
    {
        session_destroy();
    }
}