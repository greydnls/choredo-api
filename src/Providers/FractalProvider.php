<?php
/**
 * Created by PhpStorm.
 * User: squinones
 * Date: 10/23/2017
 * Time: 2:08 PM
 */

namespace Choredo\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class FractalProvider extends AbstractServiceProvider
{
    protected $provides = [ Manager::class ];
    public function register()
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer("https://" . $_SERVER['SERVER_NAME']));

        $this->getContainer()->share(Manager::class, $manager);
    }
}