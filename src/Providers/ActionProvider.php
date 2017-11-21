<?php

namespace Choredo\Providers;

use Choredo\Actions;
use Choredo\Hydrators;
use Doctrine\ORM\EntityManagerInterface;
use Choredo\Entities\Account;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ActionProvider extends AbstractServiceProvider
{
    protected $provides = [
        Actions\Family\GetFamily::class,
        Actions\Family\CreateFamily::class,
        Actions\Chore\ListChores::class,
        Actions\Auth::class
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
        // Families
        $this->container->share(Actions\Family\GetFamily::class);
        $this->container->share(Actions\Family\CreateFamily::class, function () {
            return new Actions\Family\CreateFamily(
                $this->container->get(EntityManagerInterface::class)
            );
        });

        $this->container->share(Actions\Chore\ListChores::class);

        $this->container->share(Actions\Auth::class, function(){
            $entityManager = $this->container->get(EntityManagerInterface::class);
            return new Actions\Auth($entityManager->getRepository(Account::class));
        });
    }
}
