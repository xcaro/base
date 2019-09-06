<?php

namespace Si6\Base\Exceptions;

class ResourceNotFound extends BaseException
{
    protected $statusCode = 404;

    protected $message = 'RESOURCE_NOT_FOUND';
}
