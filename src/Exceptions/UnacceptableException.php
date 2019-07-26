<?php

namespace Si6\Base\Exceptions;

class UnacceptableException extends BaseException
{
    protected $statusCode = 406;

    protected $message = 'HTTP_HEADER_SHOULD_ACCEPT_JSON';
}
