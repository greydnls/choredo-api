<?php

namespace Choredo;

use Assert\AssertionFailedException;
use Choredo\Exception\InvalidRequestException;
use Choredo\Response\BadRequestResponse;
use Choredo\Response\ServerErrorResponse;
use League\Container\ContainerAwareTrait;
use League\Container\ContainerAwareInterface;
use League\Route\RouteCollection;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class App implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?? new Container();
    }

    public function run()
    {
        try {
            $response = $this->container->get(RouteCollection::class)
                ->dispatch(
                    $this->container->get(ServerRequestInterface::class),
                    new Response()
            );
        } catch (AssertionFailedException | InvalidRequestException $e) {
            $response = new BadRequestResponse([$e->getMessage()]);
        } catch (\Throwable $e) {
            $response = new ServerErrorResponse([$e->getMessage()]);
        }

        $this->container->get(SapiEmitter::class)->emit($response);
    }
}
