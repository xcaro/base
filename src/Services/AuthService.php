<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\User;

class AuthService extends Microservices
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
        $response = $this->syncAuthorization()->post('authentication');

        $user = new User();
        $user->fill((array)($response->data ?? []));

        return $user;
    }

    /**
     * @param $userId
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function validateUserId($userId)
    {
        $this->get('users/validation', ['user_id' => $userId]);
    }
}
