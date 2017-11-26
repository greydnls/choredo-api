<?php

namespace Choredo\Providers;

use Choredo\Actions;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ActionProvider extends AbstractServiceProvider
{
    protected $provides = [
        Actions\Family\GetFamily::class,
        Actions\Family\CreateFamily::class,
        Actions\Family\ListFamilies::class,
        Actions\Chore\ListChores::class
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
        $this->container->share(Actions\Family\ListFamilies::class);
        $this->container->share(Actions\Family\CreateFamily::class);

        // Chores
        $this->container->share(Actions\Chore\ListChores::class);
    }
}
