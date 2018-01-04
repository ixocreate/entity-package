<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Entity\Entity;

use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use PHPUnit\Framework\TestCase;

class DefinitionCollectionTest extends TestCase
{
    public function testCollection()
    {
        $definition = new Definition(
            "test_string",
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
