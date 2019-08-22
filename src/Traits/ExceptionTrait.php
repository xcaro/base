<?php

namespace Si6\Base\Traits;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Si6\Base\Exceptions\BaseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ExceptionTrait
{
    use ResponseTrait;

    protected function handleValidationMessage($message)
    {
        $message = Str::upper($message);
        $message = str_replace('.', '', $message);
        $message = str_replace(' ', '_', $message);

        return $message;
    }

    protected function handleHttpExceptionMessage($exception)
    {
        $message = class_basename($exception);
        $message = Str::snake($message);
        $message = Str::upper($message);

        return $message;
    }

    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            foreach ($exception->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    $this->addError($field, $this->handleValidationMessage($error));
                }
            }
            $this->setStatusCode($exception->status);
        }

        if ($exception instanceof HttpException) {
            $this->setStatusCode($exception->getStatusCode())
                ->addError(null, $this->handleHttpExceptionMessage($exception))
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

        if (app()->environment() !== 'production' && env('APP_DEBUG') == true) {
            $this->setDebug($exception);
        }
    }
}
