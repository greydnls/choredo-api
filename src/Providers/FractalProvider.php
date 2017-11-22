<?php

namespace Choredo\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class FractalProvider extends AbstractServiceProvider
{
    protected $provides = [
        Manager::class
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
