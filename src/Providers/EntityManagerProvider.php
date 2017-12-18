<?php

declare(strict_types=1);

namespace Choredo\Providers;

use Choredo\Entities\Filters\FamilyFilter;
use Doctrine\Common;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class EntityManagerProvider.
 */
class EntityManagerProvider extends AbstractServiceProvider
{
    protected $provides = [
        ORM\EntityManagerInterface::class,
    ];

    public function register()
    {
        $this->container->share(ORM\EntityManagerInterface::class, function () {
            $environment = getenv('ENV') ?? 'production';
            $isProduction = 'production' === $environment;

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

            $config->addFilter('family', FamilyFilter::class);

            $dbParams = [
                'driver'   => 'pdo_pgsql',
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'host'     => getenv('DB_HOST') ?? 'db',
                'dbname'   => getenv('DB_DATABASE') ?? 'choredo',
            ];

            $orm = ORM\EntityManager::create($dbParams, $config);
            $orm->getFilters()->enable('family');          // This is on by default to enforce tenant segregation
        });
    }
}
