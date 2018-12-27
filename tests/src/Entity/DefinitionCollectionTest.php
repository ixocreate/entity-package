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
namespace IxocreateTest\Entity\Entity;

use Ixocreate\Entity\Entity\Definition;
use Ixocreate\Entity\Entity\DefinitionCollection;
use PHPUnit\Framework\TestCase;

class DefinitionCollectionTest extends TestCase
{
    public function testCollection()
    {
        $definition = new Definition(
            "testString",
            "string",
            false,
            false
        );

        $collection = new DefinitionCollection([$definition]);

        $this->assertEquals($definition, $collection->get("testString"));
    }

    public function testInvalidData()
    {
        $this->expectException(\Throwable::class);
        new DefinitionCollection(['id' => 1]);
    }
}
