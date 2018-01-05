<?php

declare(strict_types=1);

namespace Choredo;

use Choredo\Output\FractalAware;
use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
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

        $this->addServiceProvider(new Providers\EntityManagerProvider());
        $this->addServiceProvider(new Providers\MiddlewareProvider());
        $this->addServiceProvider(new Providers\FractalProvider());
        $this->addServiceProvider(new Providers\EntityManagerProvider());
        $this->addServiceProvider(new Providers\ActionProvider());
        $this->addServiceProvider(new Providers\RouterProvider());
        $this->addServiceProvider(new Providers\LoggerProvider());

        $this->inflector(FractalAware::class)
            ->invokeMethod('setManager', [$this->get(Manager::class)]);
        $this->inflector(EntityManagerAware::class)
            ->invokeMethod('setEntityManager', [$this->get(EntityManagerInterface::class)]);
        $this->inflector(LoggerAwareInterface::class)
            ->invokeMethod('setLogger', [$this->get(LoggerInterface::class)]);
    }
}
