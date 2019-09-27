<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use Si6\Base\Exceptions\MicroservicesException;

class AuthService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.auth');
    }

    /**
     * @param  array  $param
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function getUsers(array $param)
    {
        $response = $this->get('users', $param);

        return $response->data ?? [];
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
