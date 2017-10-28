<?php


namespace Choredo;

use Choredo\Output\FractalAwareInterface;
use League\Fractal\Manager;
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

        $this->addServiceProvider(new Providers\FractalProvider());
        $this->addServiceProvider(new Providers\ActionProvider());
        $this->addServiceProvider(new Providers\RouterProvider());
        $this->addServiceProvider(new Providers\EntityManagerProvider());
        $this->addServiceProvider(new Providers\LoggerProvider());

        $this->inflector(FractalAwareInterface::class)
            ->invokeMethod('setManager', [$this->get(Manager::class)]);
    }
}