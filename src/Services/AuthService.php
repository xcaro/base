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
     * @param  Request  $request
     * @return User
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function authenticate(Request $request)
    {
        $response = $this->post('authentication', [], [
            'headers' => [
                'Authorization' => $request->headers->get('Authorization'),
                'Content-type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);

        $user = new User();
        $user->fill((array)$response->data);

        return $user;
    }
}
