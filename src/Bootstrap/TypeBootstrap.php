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
namespace KiwiSuite\Entity\Bootstrap;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\Application\IncludeHelper;
use KiwiSuite\Entity\Type\Factory\TypeSubManagerFactory;
use KiwiSuite\Entity\Type\TypeServiceManagerConfig;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class TypeBootstrap implements BootstrapInterface
{
    /**
     * @var string
     */
    private $bootstrapFilename = 'type.php';

    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
        $serviceManagerConfigurator->addSubManager(TypeSubManager::class, TypeSubManagerFactory::class);
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapRegistry
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapRegistry): void
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator(TypeServiceManagerConfig::class);
        $bootstrapDirectories = [
            $applicationConfig->getBootstrapDirectory(),
        ];
        foreach ($applicationConfig->getBundles() as $bundle) {
            $bootstrapDirectories[] = $bundle->getBootstrapDirectory();
        }
        foreach ($bootstrapDirectories as $directory) {
            if (\file_exists($directory . $this->bootstrapFilename)) {
                IncludeHelper::include(
                    $directory . $this->bootstrapFilename,
                    ['typeConfigurator' => $serviceManagerConfigurator]
                );
            }
        }

        $bootstrapRegistry->addService(TypeServiceManagerConfig::class, $serviceManagerConfigurator->getServiceManagerConfig());
    }
}
