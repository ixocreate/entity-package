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
namespace KiwiSuite\Entity\BootstrapItem;

use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Entity\Type\TypeConfigurator;

class TypeBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new TypeConfigurator();
    }


    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'type';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'type.php';
    }
}
