<?php

namespace Choredo\Providers;

use Doctrine\Common;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class EntityManagerProvider
 * @package Choredo\Providers
 */
class EntityManagerProvider extends ServiceProvider
{
    public function register()
    {
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
            'host'     => getenv('DB_HOST') ?? 'db',
            'dbname'   => getenv('DB_DATABASE') ?? 'choredo',
        ];

        $this->app['entity_manager'] = ORM\EntityManager::create($dbParams, $config);
    }
}