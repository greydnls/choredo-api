<?php


namespace Choredo\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use Zend\Diactoros\Response\TextResponse;

class RouterProvider extends AbstractServiceProvider
{
    protected $provides = [
        RouteCollection::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        $this->container->share(
            RouteCollection::class,
            function () : RouteCollection {
                $router = new RouteCollection($this->container);

                $router->get('/', function(){
                    return new TextResponse("It's Working");
                });

                return $router;
            }
        );
    }
}