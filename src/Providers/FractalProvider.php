<?php

declare(strict_types=1);

namespace Choredo\Providers;

use Choredo\JsonApiSerializer;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Fractal\Manager;

class FractalProvider extends AbstractServiceProvider
{
    protected $provides = [
        Manager::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer(\Choredo\getBaseUrl()));
        $this->container->share(Manager::class, $manager);
    }
}
