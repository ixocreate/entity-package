<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity;

use Ixocreate\Application\Package\PackageInterface;

final class Package implements PackageInterface
{
    /**
     * @return array
     */
    public function getBootstrapItems(): array
    {
        return [];
    }

    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string
    {
        return null;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [];
    }
}
