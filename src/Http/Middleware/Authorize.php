<?php

namespace Si6\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Si6\Base\Exceptions\Forbidden;

class Authorize
{
    /**
     * @param $request
     * @param  Closure  $next
     * @param  mixed  ...$permissions
     * @return mixed
     * @throws Forbidden
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = Auth::user();
        foreach ($permissions as $permission) {
            if (!in_array($permission, $user->permissions())) {
                throw new Forbidden($permission);
            }
        }

        return $next($request);
    }
}
