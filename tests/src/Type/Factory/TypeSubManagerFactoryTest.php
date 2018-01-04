<?php
namespace KiwiSuiteTest\Entity\Type\Factory;

use KiwiSuite\Entity\Type\Factory\TypeSubManagerFactory;
use KiwiSuite\Entity\Type\TypeServiceManagerConfig;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use PHPUnit\Framework\TestCase;

class TypeSubManagerFactoryTest extends TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager(
            new ServiceManagerConfig([]),
            new ServiceManagerSetup(),
            [TypeServiceManagerConfig::class => new TypeServiceManagerConfig([])]
        );

        $typeSubManagerFactory = new TypeSubManagerFactory();
        $result = $typeSubManagerFactory->__invoke($serviceManager, TypeSubManager::class);

        $this->assertInstanceOf(TypeSubManager::class, $result);
    }
}
