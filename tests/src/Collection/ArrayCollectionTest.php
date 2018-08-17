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
namespace KiwiSuiteTest\Entity\Collection;

use KiwiSuite\Entity\Collection\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ArrayCollectionTest extends TestCase
{
    public function testDataIntegrityInvalidDataException()
    {
        $this->expectException(\Throwable::class);
        new ArrayCollection(['id' => 1]);
    }
}
