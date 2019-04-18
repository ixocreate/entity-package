<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Package\BootstrapItem;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Entity\Package\Type\TypeConfigurator;

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
