<?php

namespace Choredo;

use Assert\AssertionFailedException;
use Choredo\Response\BadRequestResponse;
use Choredo\Response\ServerErrorResponse;
use League\Container\ContainerInterface;
use League\Container\ServiceProvider\ServiceProviderInterface;
use League\Route\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class App
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?? new Container();
    }

    public function run()
    {
        try {
            $response = $this->container->get(RouteCollection::class)->dispatch(
                $this->container->get(ServerRequestInterface::class),
                new Response()
            );
        } catch (AssertionFailedException $e) {
            $response = new BadRequestResponse([$e->getMessage()]);
        } catch (\Throwable $e){
            $response = new ServerErrorResponse([$e->getMessage()]);
        }

        $this->container->get(SapiEmitter::class)->emit($response);
    }

    public function register(ServiceProviderInterface $serviceProvider)
    {
        $this->container->addServiceProvider($serviceProvider);
    }
}