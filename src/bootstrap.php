<?php

use Choredo\Output\FractalAwareInterface;
use League\Fractal\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use Choredo\Providers;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$container = new \League\Container\Container();
$container->share(ResponseInterface::class, new Response());
$container->share(SapiEmitter::class, SapiEmitter::class);
$container->share(
    ServerRequestInterface::class,
    function () {
        return ServerRequestFactory::fromGlobals();
    }
);

$container->addServiceProvider(new Providers\EntityManagerProvider());
$container->addServiceProvider(new Providers\RouterProvider());
$container->addServiceProvider(new Providers\LoggerProvider());

$this->inflector(FractalAwareInterface::class)->invokeMethod('setManager', [Manager::class]);

$app = new \Choredo\App($container);

return $app;
