<?php


namespace Choredo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

final class Container extends \League\Container\Container
{
    public function __construct()
    {
        parent::__construct();

        $this->share(ResponseInterface::class, new Response());
        $this->share(SapiEmitter::class, SapiEmitter::class);
        $this->share(
            ServerRequestInterface::class,
            function () {
                return ServerRequestFactory::fromGlobals();
            }
        );

        $this->addServiceProvider(new Providers\RouterProvider());
        $this->addServiceProvider(new Providers\EntityManagerProvider());
    }
}