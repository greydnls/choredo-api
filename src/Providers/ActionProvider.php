<?php

declare(strict_types=1);

namespace Choredo\Providers;

use Choredo\Actions;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ActionProvider extends AbstractServiceProvider
{
    protected $provides = [
        Actions\Register::class,
        Actions\Family\GetFamily::class,
        Actions\Family\CreateFamily::class,
        Actions\Family\ListFamilies::class,
        Actions\Account\GetAccount::class,
        Actions\Account\ListAccounts::class,
        Actions\Chore\ListChores::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        // Register
        $this->container->share(Actions\Register::class);

        // Families
        $this->container->share(Actions\Family\GetFamily::class);
        $this->container->share(Actions\Family\ListFamilies::class);
        $this->container->share(Actions\Family\CreateFamily::class);

        // Accounts
        $this->container->share(Actions\Account\ListAccounts::class);
        $this->container->share(Actions\Account\GetAccount::class);

        // Chores
        $this->container->share(Actions\Chore\ListChores::class);
    }
}
