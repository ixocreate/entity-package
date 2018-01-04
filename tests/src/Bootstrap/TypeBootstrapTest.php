<?php
/**
 * kiwi-suite/application-console (https://github.com/kiwi-suite/application-console)
 *
 * @package kiwi-suite/application-console
 * @see https://github.com/kiwi-suite/application-console
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Entity\Bootstrap;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\Entity\Bootstrap\TypeBootstrap;
use KiwiSuite\Entity\Type\TypeServiceManagerConfig;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use KiwiSuiteMisc\Entity\BundleTest;
use PHPUnit\Framework\TestCase;

class TypeBootstrapTest extends TestCase
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfig(
            true,
            null,
            __DIR__ . '/../../bootstrap',
            null,
            null,
            null,
            [],
            [BundleTest::class]
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testBootstrap()
    {
        $bootstrapRegistry = new BootstrapRegistry($this->applicationConfig->getModules());

        $typeBootstrap = new TypeBootstrap();
        $typeBootstrap->bootstrap($this->applicationConfig, $bootstrapRegistry);

        $this->assertTrue($bootstrapRegistry->hasService(TypeServiceManagerConfig::class));
    }

    public function testConfigureServiceManager()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $typeBootstrap = new TypeBootstrap();
        $typeBootstrap->configureServiceManager($serviceManagerConfigurator);

        $this->assertArrayHasKey(TypeSubManager::class, $serviceManagerConfigurator->getSubManagers());
    }
}
