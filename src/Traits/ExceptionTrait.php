<?php

namespace Si6\Base\Traits;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Si6\Base\Exceptions\BaseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ExceptionTrait
{
    use ResponseTrait;

    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            foreach ($exception->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    $this->addError($field, $error);
                }
            }
            $this->setStatusCode($exception->status);
        }

        if ($exception instanceof HttpException) {
            $this->setStatusCode($exception->getStatusCode())
                ->addError(null, class_basename($exception))
                ->setHeaders($exception->getHeaders());
        }

        if ($exception instanceof BaseException) {
            $this->setStatusCode($exception->getStatusCode())
                ->addError(null, $exception->getMessage());
        }

        if (!$this->errors) {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->addError(null, 'INTERNAL_SERVER_ERROR');
        }
    }
}
