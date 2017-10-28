<?php


namespace Choredo\Providers;

use Choredo\Actions;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use League\Route\RouteGroup;
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
            function (): RouteCollection {
                $router = new RouteCollection($this->container);

                $router->get('/', function () {
                    return new TextResponse("It's Working");
                });

                $router->group('families/{familyId:uuid}', function (RouteGroup $routeGroup) {
                    $routeGroup->get('/', [Actions\Family\GetFamily::class, '__invoke']);
                });

                return $router;
            }
        );
    }
}