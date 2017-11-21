<?php


namespace Choredo\Providers;

use Choredo\Actions;
use Choredo\Middleware\FamilyHydrator;
use Choredo\Middleware\JsonApiResourceParser;
use Choredo\Middleware\MultiTenantFamilyHydrator;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use League\Route\RouteGroup;
use Zend\Diactoros\Response;
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

                $router->post('/auth', [Actions\Auth::class, '__invoke']);

                $router->post('/families', [Actions\Family\CreateFamily::class, '__invoke'])
                    ->middlewares(
                        new FamilyHydrator(),
                        JsonApiResourceParser::newType('families')
                    );

                $router->group('families/{familyId:uuid}', function (RouteGroup $routeGroup) {
                    $routeGroup->get('/', [Actions\Family\GetFamily::class, '__invoke']);
                    $routeGroup->get('chores', [Actions\Chore\ListChores::class, '__invoke']);
                    $routeGroup->get('/chores/{choreId}', [Actions\Chore\GetChore::class, '__invoke']);
                    $routeGroup->post('/chores/', [Actions\Chore\CreateChore::class, '__invoke']);
                    $routeGroup->put('/chores/{choreId}', [Actions\Chore\UpdateChore::class, '__invoke']);
                    $routeGroup->delete('/chores/{choreId}', [Actions\Chore\DeleteChore::class, '__invoke']);
                })->middleware($this->container->get(MultiTenantFamilyHydrator::class));

                return $router;
            }
        );
    }
}
