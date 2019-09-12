<?php

namespace Si6\Base\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Si6\Base\Exceptions\Handler;
use Si6\Base\Http\Middleware\Authenticate;
use Si6\Base\Http\Middleware\BeforeResponse;
use Si6\Base\Http\Middleware\Unacceptable;
use Si6\Base\Http\Middleware\Unsupported;
use Si6\Base\Http\Middleware\Versioning;

class Si6BaseServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(Unsupported::class);
        $kernel->prependMiddleware(Unacceptable::class);
        $kernel->prependMiddleware(BeforeResponse::class);

        $this->mergeConfigFrom(__DIR__ . '/../../config/microservices.php', 'microservices');
        $this->app->bind(ClientInterface::class, function ($app, $options) {
            return new Client($options);
        });

        $this->mergeConfigFrom(__DIR__ . '/../../config/auth.php', 'auth');
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('versioning', Versioning::class);
        $router->aliasMiddleware('auth', Authenticate::class);
    }
}
