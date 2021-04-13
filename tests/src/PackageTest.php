<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity;

use Ixocreate\Entity\Package;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    /**
     * @covers \Ixocreate\Entity\Package
     */
    public function testPackage()
    {
        $package = new Package();

        $this->assertEmpty($package->getBootstrapItems());
        $this->assertEmpty($package->getDependencies());
        $this->assertNull($package->getBootstrapDirectory());
    }
}
