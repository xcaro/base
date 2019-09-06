<?php

namespace Si6\Base\Exceptions;

class VersionInvalid extends BaseException
{
    protected $statusCode = 400;

    public function __construct(string $version = "")
    {
        $message = "We don't support api version $version";

        if (!$version) {
            $message = 'API version is required';
        }

        parent::__construct($message);
    }
}
