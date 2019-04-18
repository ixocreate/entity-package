<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Bootstrap;

use Ixocreate\Application\Service\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;
use Ixocreate\Entity\Type\TypeConfigurator;

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
