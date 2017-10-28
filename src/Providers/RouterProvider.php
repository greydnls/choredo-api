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
            function () : RouteCollection {
                $router = new RouteCollection($this->container);

                $router->get(
                    '/',
                    function () {
                        return new TextResponse("It's Working");
                    }
                );

                $router->group('families/{familyId}', function(RouteGroup $route){
                    $route->get('chores',  [Actions\Chore\ListChores::class, '__invoke']);
                    $route->get('/chores/{choreId}',  [Actions\Chore\GetChore::class, '__invoke']);
                    $route->post('/chores/',  [Actions\Chore\CreateChore::class, '__invoke']);
                    $route->put('/chores/{choreId}',  [Actions\Chore\UpdateChore::class, '__invoke']);
                    $route->delete('/chores/{choreId}',  [Actions\Chore\DeleteChore::class, '__invoke']);

                });

                return $router;
            }
        );
    }
}