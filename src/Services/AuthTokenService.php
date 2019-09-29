<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\User;

class AuthTokenService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.auth');
    }

    /**
     * @return User
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function authenticate()
    {
        $response = $this->post('authentication');

        $user = new User();
        $user->fill((array)($response->data ?? []));

        return $user;
    }
}
