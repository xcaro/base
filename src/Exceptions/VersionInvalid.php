<?php

namespace Si6\Base\Exceptions;

use Illuminate\Support\Str;

class VersionInvalid extends BaseException
{
    protected $statusCode = 400;

    public function __construct(string $version = "")
    {
        $message = "WE_DO_NOT_SUPPORT_VERSION_" . Str::upper($version);

        if (!$version) {
            $message = 'API_VERSION_IS_REQUIRED';
        }

        parent::__construct($message);
    }
}
