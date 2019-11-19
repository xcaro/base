<?php

namespace Si6\Base\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected $code = 0;

    protected $message = 'INTERNAL_SERVER_ERROR';

    protected $statusCode = 500;

    protected $field = '';

    public function __construct(string $message = "")
    {
        parent::__construct($message ?: $this->message, $this->code);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function getField()
    {
        return $this->field;
    }
}
