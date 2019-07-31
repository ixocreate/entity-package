<?php

use Ixocreate\Application\Service\ServiceManagerConfig;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Benchmark\Cms\TestEntity;
use Ixocreate\Benchmark\Cms\TypeFactory;
use Ixocreate\Schema\Type\DateTimeType;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\Schema\Type\TypeSubManager;
use Ixocreate\Schema\Type\UuidType;
use Ixocreate\ServiceManager\ServiceManager;
use Ixocreate\ServiceManager\ServiceManagerSetup;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/TestEntity.php';
require_once __DIR__ . '/TypeFactory.php';

$serviceManagerConfigurator = new ServiceManagerConfigurator();
$serviceManagerConfigurator->addService(UuidType::class, TypeFactory::class);
$serviceManagerConfigurator->addService(DateTimeType::class, TypeFactory::class);

$typeSubManager = new TypeSubManager(
    new ServiceManager(
        new ServiceManagerConfig(new ServiceManagerConfigurator()),
        new ServiceManagerSetup()
    ),
    new ServiceManagerConfig($serviceManagerConfigurator),
    TypeInterface::class
);
Type::initialize($typeSubManager);

for ($i=0; $i< 10000; $i++) {
    new TestEntity([
        'id' => '4c67bc96-6fa4-4951-ad6a-81e6e582ac00',
        'name' => 'test',
        'updatedAt' => '2018-08-10 16:49:00',
        'createdAt' => new DateTime()
    ]);
}

