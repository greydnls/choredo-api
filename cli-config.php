#!/usr/bin/env php
<?php

use Choredo\App;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console;

// replace with file to your own project bootstrap
/** @var App $app */
$app = require_once __DIR__ . '/src/bootstrap.php';

/** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
$entityManager = $app->getContainer()->get(\Doctrine\ORM\EntityManager::class);

$helperSet = ConsoleRunner::createHelperSet($entityManager);

$helperSet->set(new Console\Helper\QuestionHelper(), 'dialog');

$application = new Console\Application();
$application->setHelperSet($helperSet);

$commands = [
    // Doctrine ORM
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\CollectionRegionCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\EntityRegionCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryRegionCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),

    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),

    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
    new \Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),

    // Doctrine DBAL
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),

    // Doctrine Migrations
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
];

$application->addCommands($commands);
$application->run();
