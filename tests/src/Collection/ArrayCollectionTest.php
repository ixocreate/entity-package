<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Entity\Collection;

use Ixocreate\Entity\Collection\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ArrayCollectionTest extends TestCase
{
    public function testDataIntegrityInvalidDataException()
    {
        $this->expectException(\Throwable::class);
        new ArrayCollection(['id' => 1]);
    }
}
