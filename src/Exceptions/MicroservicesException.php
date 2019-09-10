<?php

namespace Si6\Base\Exceptions;

class MicroservicesException extends BaseException
{
    protected $message = 'MICROSERVICES_REQUEST_ERROR';

    protected $dataResponse;

    public function __construct($data, $statusCode)
    {
        parent::__construct();
        $this->statusCode = $statusCode;
        $this->dataResponse = $data;
    }

    public function errors()
    {
        if (empty($this->dataResponse->errors)) {
            return [];
        }

        if (is_array($this->dataResponse->errors)) {
            return $this->dataResponse->errors;
        }

        return [['message' => 'MICROSERVICES_REQUEST_ERROR']];
    }
}
