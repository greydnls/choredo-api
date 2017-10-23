<?php

use Laravel\Lumen\Routing\Router;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Choredo\Exceptions\Handler::class
);

$app->routeMiddleware([
    'auth' => Choredo\Http\Middleware\Authenticate::class,
]);

$app->register(Choredo\Providers\AppServiceProvider::class);
$app->register(Choredo\Providers\AuthServiceProvider::class);
$app->register(LaravelDoctrine\ORM\DoctrineServiceProvider::class);
$app->register(Choredo\Providers\EntityManagerProvider::class);


$app->router->group([
    'namespace' => 'Choredo\Http\Controllers',
], function (Router $router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
});

$app->run();
