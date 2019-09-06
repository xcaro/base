<?php

namespace Si6\Base;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Si6\Base\Exceptions\Handler;
use Si6\Base\Http\Middleware\BeforeResponse;
use Si6\Base\Http\Middleware\Unacceptable;
use Si6\Base\Http\Middleware\Unsupported;
use Si6\Base\Http\Middleware\Versioning;

class Si6BaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(Unsupported::class);
        $kernel->prependMiddleware(Unacceptable::class);
        $kernel->prependMiddleware(BeforeResponse::class);
    }

    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('versioning', Versioning::class);
    }
}
