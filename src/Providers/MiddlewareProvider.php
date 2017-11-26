<?php


namespace Choredo\Providers;

use Choredo\Entities\Family;
use Choredo\Middleware\FamilyEntityLoader;
use Doctrine\ORM\EntityManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MiddlewareProvider extends AbstractServiceProvider
{
    protected $provides = [
        FamilyEntityLoader::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->container->share(FamilyEntityLoader::class, function () {
            $entityManager = $this->container->get(EntityManagerInterface::class);

            return new FamilyEntityLoader($entityManager->getRepository(Family::class));
        });
    }
}
