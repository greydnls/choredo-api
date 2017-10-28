<?php


namespace Choredo\Providers;


use Choredo\Entities\Family;
use Choredo\Middleware\MultiTenantFamilyMiddleware;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MiddlewareProvider extends AbstractServiceProvider
{
    protected $provides = [ MultiTenantFamilyMiddleware::class ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->container->share(MultiTenantFamilyMiddleware::class, function(){
            $entityManager = $this->container->get(EntityManager::class);
            return new MultiTenantFamilyMiddleware($entityManager->getRepository(Family::class));
        });
    }
}