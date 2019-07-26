<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class Unsupported
{
    /**
     * @param $request
     * @param  Closure  $next
     * @return mixed
     * @throws UnsupportedMediaTypeHttpException
     */
    public function handle($request, Closure $next)
    {
        $type = $request->headers->get('content-type');

        if (strpos($type, 'application/json') !== 0) {
            throw new UnsupportedMediaTypeHttpException;
        }

        return $next($request);
    }
}
