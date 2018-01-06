<?php

declare(strict_types=1);

namespace Choredo\Providers;

use Choredo\Actions;
use Choredo\Entities\Chore;
use Choredo\Hydrators\AccountHydrator;
use Choredo\Hydrators\ChildHydrator;
use Choredo\Hydrators\ChoreHydrator;
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
                $router->post('/register', [Actions\Register::class, '__invoke'])
                    ->middlewares(ResourceHydrator::newType('accounts', new AccountHydrator()));

                $router->get('families', [Actions\Family\ListFamilies::class, '__invoke']);
                $router->post('/families', [Actions\Family\CreateFamily::class, '__invoke'])
                    ->middleware(ResourceHydrator::newType('families', new FamilyHydrator()));

                $router->group('families/{familyId:uuid}', function (RouteGroup $routeGroup) {
                    $routeGroup->get('/', [Actions\Family\GetFamily::class, '__invoke']);
                    $routeGroup->get('/accounts/{accountId:uuid}', [Actions\Account\GetAccount::class, '__invoke']);
                    $routeGroup->get('/accounts', [Actions\Account\ListAccounts::class, '__invoke']);

                    // Children
                    $routeGroup->get('children', [Actions\Child\ListChildren::class, '__invoke']);
                    $routeGroup->get('children/{childId:uuid}', [Actions\Child\GetChild::class, '__invoke']);

                    // Chores
                    $routeGroup->get('chores', [Actions\Chore\ListChores::class, '__invoke']);
                    $routeGroup->get('/chores/{choreId}', [Actions\Chore\GetChore::class, '__invoke']);
                    $routeGroup->delete('/chores/{choreId}', [Actions\Chore\DeleteChore::class, '__invoke']);
                })->middleware($this->container->get(FamilyEntityLoader::class));

                // Create Routes
                $router->post('/families/{familyId:uuid}/children', [Actions\Child\CreateChild::class, '__invoke'])
                       ->middleware(ResourceHydrator::newType('children', new ChildHydrator()))
                       ->middleware($this->container->get(FamilyEntityLoader::class))
                ;

                $router->post('/families/{familyId:uuid}/chores', [Actions\Chore\CreateChore::class, '__invoke'])
                       ->middleware(ResourceHydrator::newType(Chore::API_ENTITY_TYPE, new ChoreHydrator()))
                       ->middleware($this->container->get(FamilyEntityLoader::class))
                ;

                // Update Routes
                $router->put(
                    '/families/{familyId:uuid}/chores/{choreId:uuid}',
                    [Actions\Chore\UpdateChore::class, '__invoke']
                )->middleware(ResourceHydrator::uuidType(Chore::API_ENTITY_TYPE, new ChoreHydrator()))
                       ->middleware($this->container->get(FamilyEntityLoader::class))
                ;

                // Global middleware
                $router->middleware(new PaginationParser());
                $router->middleware(new SortParser());
                $router->middleware(new FilterParser());

                return $router;
            }
        );
    }
}
