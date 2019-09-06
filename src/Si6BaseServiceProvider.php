<?php

namespace Si6\Base;

use App\Http\Middleware\Versioning;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Si6\Base\Exceptions\Handler;
use Si6\Base\Http\Middleware\BeforeResponse;
use Si6\Base\Http\Middleware\Unacceptable;
use Si6\Base\Http\Middleware\Unsupported;

class Si6BaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);
//        $this->app->middleware([
//            Unsupported::class,
//            Unacceptable::class,
//            BeforeResponse::class,
//        ]);
    }

    public function boot()
    {
        $router = app(Router::class);
        $router->aliasMiddleware('versioning', Versioning::class);
    }
}
