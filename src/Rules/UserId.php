<?php

namespace Si6\Base\Rules;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\Services\AuthService;

class UserId implements Rule
{
    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function passes($attribute, $value)
    {
        /** @var AuthService $authService */
        $authService = app(AuthService::class)->getInstance();

        $authService->validateUserId($value);

        return true;
    }

    public function message()
    {
        return 'The :attribute must be a kana string.';
    }
}
