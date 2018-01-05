<?php

declare(strict_types=1);

namespace Choredo\Providers;

use Choredo\Middleware\FamilyEntityLoader;
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
     */
    public function register()
    {
        $this->container->share(FamilyEntityLoader::class);
    }
}
