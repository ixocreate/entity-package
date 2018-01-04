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
