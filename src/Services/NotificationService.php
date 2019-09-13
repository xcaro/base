<?php

namespace Si6\Base\Services;

class NotificationService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.notification');
    }
}
