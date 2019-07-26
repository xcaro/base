<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class BeforeResponse
{
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        // make some changing before response

        return $response;
    }
}
