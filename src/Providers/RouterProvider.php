<?php


namespace Choredo\Providers;

use Choredo\Actions;
use Choredo\Hydrators\FamilyHydrator;
use Choredo\Middleware\FamilyEntityLoader;
use Choredo\Middleware\FilterParser;
use Choredo\Middleware\PaginationParser;
use Choredo\Middleware\ResourceHydrator;
use Choredo\Middleware\SortParser;
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

                $router->get('families', [Actions\Family\ListFamilies::class, '__invoke']);
                $router->post('/families', [Actions\Family\CreateFamily::class, '__invoke'])
                    ->middleware(ResourceHydrator::newType('families', new FamilyHydrator()));

                $router->group('families/{familyId:uuid}', function (RouteGroup $routeGroup) {
                    $routeGroup->get('/', [Actions\Family\GetFamily::class, '__invoke']);
                    $routeGroup->delete('/', [Actions\Family\DeleteFamily::class, '__invoke']);
                    $routeGroup->get('chores', [Actions\Chore\ListChores::class, '__invoke']);
                    $routeGroup->get('/chores/{choreId}', [Actions\Chore\GetChore::class, '__invoke']);
                    $routeGroup->post('/chores/', [Actions\Chore\CreateChore::class, '__invoke']);
                    $routeGroup->put('/chores/{choreId}', [Actions\Chore\UpdateChore::class, '__invoke']);
                    $routeGroup->delete('/chores/{choreId}', [Actions\Chore\DeleteChore::class, '__invoke']);
                })->middleware($this->container->get(FamilyEntityLoader::class));

                // Global middleware
                $router->middleware(new PaginationParser());
                $router->middleware(new SortParser());
                $router->middleware(new FilterParser());

                return $router;
            }
        );
    }
}
