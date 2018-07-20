<?php
namespace core;

class Session
{
    public $data;

    public function __construct()
    {
        session_start();
        $this->data = $_SESSION;
    }

    public function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            $this->data[$key] = $_SESSION[$key];
            return $this->data[$key];
        }
        else
        {
            return null;
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return true;
    }

    public function __destruct()
    {
        session_destroy();
    }
}