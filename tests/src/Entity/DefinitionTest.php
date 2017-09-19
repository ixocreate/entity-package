<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Entity\Entity;

use KiwiSuite\Entity\Entity\Definition;
use PHPUnit\Framework\TestCase;

class DefinitionTest extends TestCase
{
    public function testDefintion()
    {
        $definition = new Definition(
            "test_string",
            "string",
            false,
            false
        );

        $this->assertEquals("testString", $definition->getName());
        $this->assertEquals("string", $definition->getType());
        $this->assertFalse($definition->isNullAble());
        $this->assertFalse($definition->isPublic());
    }

    public function testDefaultDefinitionValues()
    {
        $definition = new Definition(
            "test_string",
            "string"
        );

        $this->assertTrue($definition->isNullAble());
        $this->assertTrue($definition->isPublic());
    }
}
