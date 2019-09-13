<?php

namespace Si6\Base\Services;

class UserService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.user');
    }
}
