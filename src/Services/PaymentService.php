<?php

namespace Si6\Base\Services;

class PaymentService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.payment');
    }
}
