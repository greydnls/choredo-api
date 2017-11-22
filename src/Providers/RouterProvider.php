<?php


namespace Choredo\Providers;

use Choredo\Actions;
use Choredo\Hydrators\FamilyHydrator;
use Choredo\Middleware\JsonApiResourceParser;
use Choredo\Middleware\MultiTenantFamilyHydrator;
use Choredo\Middleware\ResourceHydrator;
use Choredo\Route\RouteCollection;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteGroup;

class RouterProvider extends AbstractServiceProvider
{
    protected $provides = [
        \League\Route\RouteCollection::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        $this->container->share(
            \League\Route\RouteCollection::class,
            function (): RouteCollection {
                $router = new RouteCollection($this->container);

                $router->post('/families', [Actions\Family\CreateFamily::class, '__invoke'])
                    ->middlewares(
                        new ResourceHydrator(new FamilyHydrator()),
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
