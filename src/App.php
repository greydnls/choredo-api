<?php

namespace Choredo;

use Assert\AssertionFailedException;
use Choredo\Exception\InvalidRequestException;
use Choredo\Response\BadRequestResponse;
use Choredo\Response\ServerErrorResponse;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Route\Http\Exception\MethodNotAllowedException;
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

            /** @var RouteCollection $router */
            $router = $this->container->get(RouteCollection::class);
            $dispatcher = $router->getDispatcher($request);

            $result = $dispatcher->dispatch(
                $request->getMethod(),
                $request->getUri()->getPath()
            );

            if ($result[0] === \FastRoute\Dispatcher::FOUND) {
                $request = $request
                    ->withAttribute(REQUEST_HANDLER_CLASS, get_class($result[1][0]->getCallable()[0]))
                    ->withAttribute(REQUEST_VARIABLES, $result[2]);
            }

            $response = $router->dispatch($request, new Response());
        } catch (AssertionFailedException | InvalidRequestException $e) {
            $response = new BadRequestResponse([$e->getMessage()]);
        } catch (MethodNotAllowedException $e) {
            if ($request->getMethod() !== 'OPTIONS') {
                throw $e;

            }
            $response = new Response\JsonResponse([], 200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET,PUT,POST,DELETE,OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Content-Length, X-Requested-With',
            ]);
        } catch (\Throwable $e) {
            $response = new ServerErrorResponse([$e->getMessage()]);
        }

        $this->container->get(SapiEmitter::class)->emit($response);
    }
}
