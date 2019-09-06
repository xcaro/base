<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Si6\Base\Exceptions\VersionInvalid;

class Versioning
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws VersionInvalid
     */
    public function handle(Request $request, Closure $next)
    {
        $versions = config('version.support');
        $current = $request->segment(1) ?: '';

        if (!in_array($current, $versions, true)) {
            throw new VersionInvalid($current);
        }

        return $next($request);
    }
}
