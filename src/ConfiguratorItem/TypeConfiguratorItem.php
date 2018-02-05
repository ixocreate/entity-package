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
namespace KiwiSuite\Entity\ConfiguratorItem;

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\Entity\Type\Type\EmailType;
use KiwiSuite\Entity\Type\TypeServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class TypeConfiguratorItem implements ConfiguratorItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator(TypeServiceManagerConfig::class);
        $serviceManagerConfigurator->addFactory(EmailType::class);

        return $serviceManagerConfigurator;
    }

    /**
     * @return string
     */
    public function getConfiguratorName(): string
    {
        return 'typeConfigurator';
    }

    /**
     * @return string
     */
    public function getConfiguratorFileName(): string
    {
        return 'type.php';
    }

    /**
     * @param ServiceManagerConfigurator $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        return $configurator->getServiceManagerConfig();
    }
}
