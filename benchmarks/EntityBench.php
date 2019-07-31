<?php
declare(strict_types=1);

namespace Ixocreate\Benchmark\Cms;

use DateTime;
use Ixocreate\Application\Service\ServiceManagerConfig;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Schema\Type\DateTimeType;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\Schema\Type\TypeSubManager;
use Ixocreate\Schema\Type\UuidType;
use Ixocreate\ServiceManager\ServiceManager;
use Ixocreate\ServiceManager\ServiceManagerSetup;
use Ramsey\Uuid\Uuid;

/**
 * @BeforeMethods({"init"})
 */
class EntityBench
{
    public function init()
    {
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
    }
    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchEntity()
    {
        $test = new TestEntity([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'test',
            'updatedAt' => '2018-08-10 16:49:00',
            'createdAt' => '2018-08-10 16:49:00'
        ]);

        $test->id();
    }
}
