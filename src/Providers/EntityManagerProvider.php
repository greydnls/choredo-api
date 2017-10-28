<?php

namespace Choredo\Providers;

use Doctrine\Common;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class EntityManagerProvider
 *
 * @package Choredo\Providers
 */
class EntityManagerProvider extends AbstractServiceProvider
{
    protected $provides = [
        ORM\EntityManager::class
    ];

    public function register()
    {
        $this->container->share(
            ORM\EntityManager::class,
            function () {
                $environment = getenv('ENV') ?? 'production';
                $isProduction = $environment === 'production';

            $entityDirectory = realpath(__DIR__ . '/../Entities');
            $proxyDirectory = realpath(__DIR__ . '/../proxies/');

            Type::addType('uuid', UuidType::class);
            $cache = new Common\Cache\ArrayCache();

            $config = new ORM\Configuration();
            $annotationDriver = $config->newDefaultAnnotationDriver($entityDirectory, false);
            $config->setMetadataDriverImpl($annotationDriver);
            $config->setQueryCacheImpl($cache);
            $config->setMetadataCacheImpl($cache);
            $config->setProxyDir($proxyDirectory);
            $config->setProxyNamespace('Choredo\Proxies');

            if ($isProduction) {
                $config->setAutoGenerateProxyClasses(Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);
            } else {
                $config->setAutoGenerateProxyClasses(Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS);
            }

            $dbParams = [
                'driver'   => 'pdo_pgsql',
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'host'     => getenv('DB_HOST'),
                'dbname'   => getenv('DB_DATABASE'),
            ];

            return ORM\EntityManager::create($dbParams, $config);
        });
    }
}