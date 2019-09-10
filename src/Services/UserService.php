<?php

namespace Si6\Base\Services;

class UserService extends Microservices
{
    protected function getHost()
    {
        return config('microservices.host.user');
    }
}
