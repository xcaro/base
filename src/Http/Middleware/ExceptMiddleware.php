<?php

namespace Si6\Base\Http\Middleware;

use Illuminate\Http\Request;

trait ExceptMiddleware
{
    protected $defaultExcepts = [
        '*/v*/monitoring/*'
    ];

    /**
     * Determine if the request has a URI that should pass through middleware.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        $excepts = $this->excepts ?? [];

        $excepts = array_merge($this->defaultExcepts, $excepts);

        foreach ($excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
