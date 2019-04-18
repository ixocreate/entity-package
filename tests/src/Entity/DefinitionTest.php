<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity\Entity;

use Ixocreate\Entity\Definition;
use PHPUnit\Framework\TestCase;

class DefinitionTest extends TestCase
{
    public function testDefinition()
    {
        $definition = new Definition(
            "testString",
            "string",
            false,
            false,
            true
        );

        $this->assertEquals("testString", $definition->getName());
        $this->assertEquals("string", $definition->getType());
        $this->assertFalse($definition->isNullAble());
        $this->assertFalse($definition->isPublic());
    }

    public function testDefaultDefinitionValues()
    {
        $definition = new Definition(
            "testString",
            "string"
        );

        $this->assertTrue($definition->isNullAble());
        $this->assertTrue($definition->isPublic());
    }
}
