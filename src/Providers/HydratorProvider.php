<?php

namespace Choredo\Providers;

use Choredo\Hydrators\FamilyHydrator;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HydratorProvider extends AbstractServiceProvider
{

    protected $provides = [
        FamilyHydrator::class
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
        $this->container->share(FamilyHydrator::class);
    }
}
