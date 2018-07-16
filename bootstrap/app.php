<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->bind('path.public', function() {
    return __DIR__ . 'public/';
});

 $app->withFacades();

 $app->withEloquent();

//config
$app->configure('auth');
$app->configure('jwt');
$app->configure('api');
$app->configure('debugbar');
$app->configure('modules');
$app->configure('fractal');
$app->configure('database');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

 $app->middleware([
    App\Http\Middleware\ExampleMiddleware::class
 ]);

 $app->routeMiddleware([
     'auth' => App\Http\Middleware\Authenticate::class,
 ]);

 $app->routeMiddleware([
     'profile_json_response' => App\Http\Middleware\ProfileJsonResponse::class,
 ]);


/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

 $app->register(App\Providers\AppServiceProvider::class);
 $app->register(App\Providers\AuthServiceProvider::class);
 $app->register(App\Providers\EventServiceProvider::class);
 if (env('APP_DEBUG')) {
     $app->register(Barryvdh\Debugbar\LumenServiceProvider::class);
 }
 $app->register(Dingo\Api\Provider\LumenServiceProvider::class);
 $app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
 $app->register(Spatie\Fractal\FractalServiceProvider::class);
 app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
     return new Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
 });
 $app->register(\Nwidart\Modules\LumenModulesServiceProvider::class);
 $app->register(Illuminate\Redis\RedisServiceProvider::class);


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
    require __DIR__.'/../routes/v1.php';
});

return $app;
