<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Si6\Base\Exceptions\UnacceptableException;

class Unacceptable
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws UnacceptableException
     */
    public function handle($request, Closure $next)
    {
        $accept = $request->headers->get('accept');

        if ($accept && stripos($accept, 'json') === false) {
            throw new UnacceptableException;
        }

        return $next($request);
    }
}
