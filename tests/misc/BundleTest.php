<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @link https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteMisc\Entity;

use KiwiSuite\Application\Bundle\BundleInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class BundleTest implements BundleInterface
{

    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return "test";
    }

    /**
     * @return string
     */
    public function getBootstrapDirectory(): string
    {
        return "";
    }
}
