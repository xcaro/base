<?php

namespace Si6\Base\Services;

trait SingletonInstance
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
