<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
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
