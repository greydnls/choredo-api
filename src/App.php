<?php

namespace Choredo;

use Assert\AssertionFailedException;
use Choredo\Exception\InvalidRequestException;
use Choredo\Response\BadRequestResponse;
use Choredo\Response\MethodNotAllowedResponse;
use Choredo\Response\NotFoundResponse;
use Choredo\Response\ServerErrorResponse;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
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
            /** @var ServerRequestInterface $request */
            $request = $this->container->get(ServerRequestInterface::class);
            $response = new Response();
            $response = $this->container->get(RouteCollection::class)->dispatch($request, $response);
        } catch (AssertionFailedException $e) {
            $response = new BadRequestResponse([$e->getMessage()]);
        } catch (NotFoundException $e) {
            $response = new NotFoundResponse();
        } catch (MethodNotAllowedException $e) {
            $response = ($request->getMethod() === 'OPTIONS')
                ? new Response\JsonResponse([], 200, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods' => 'GET,PUT,POST,DELETE,OPTIONS',
                    'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Content-Length, X-Requested-With',
                ])
                : new MethodNotAllowedResponse();
        } catch (\Throwable $e) {
            $response = new ServerErrorResponse([$e->getMessage()]);
        }

        $this->container->get(SapiEmitter::class)->emit($response);
    }
}
