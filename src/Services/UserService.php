<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use Si6\Base\Exceptions\MicroservicesException;

class UserService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.user');
    }

    /**
     * @param $id
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function detail($id)
    {
        $response = $this->get('users/' . $id);

        return $response->data ?? null;
    }

    /**
     * @param $id
     * @param  array  $param
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function updateBalance($id, array $param)
    {
        $this->put("users/$id/balance", $param);
    }

    /**
     * @param  array  $param
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function getProfiles(array $param)
    {
        $response = $this->get('profiles', $param);

        return $response->data ?? null;
    }
}
