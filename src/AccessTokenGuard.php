<?php

namespace Si6\Base;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;

class AccessTokenGuard implements Guard
{
    use GuardHelpers;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        return true;
    }
}
