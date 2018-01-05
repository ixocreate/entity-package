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
use KiwiSuite\Entity\Entity\Entity;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;
use KiwiSuite\Entity\Exception\EmptyException;
use KiwiSuite\Entity\Exception\InvalidPropertyException;
use KiwiSuite\Entity\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

class EntityTraitTest extends TestCase
{
    public function testValidEntity()
    {
        $data = [
            'name' => 'something',
        ];

        $mock = new class($data) implements EntityInterface {
            use EntityTrait;

            private $name;

            private $type;

            private $category;

            private function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([
                    new Definition("name", TypeInterface::TYPE_STRING, false),
                    new Definition("type", TypeInterface::TYPE_INT, true),
                    new Definition("category", TypeInterface::TYPE_INT, false, true, true, 1),
                ]);
            }
        };

        $this->assertSame("something", $mock->name);
        $this->assertNull($mock->type);
        $this->assertSame(1, $mock->category);

        $this->assertSame([
            'name' => 'something',
            'type' => null,
            'category' => 1,
        ], $mock->toArray());

        $this->assertFalse(isset($mock->doesntExists));
        $this->assertTrue(isset($mock->category));

        $newMock = $mock->with("name", "somethingelse");
        $this->assertSame("somethingelse", $newMock->name);
    }

    public function testInvalidPropertyException()
    {
        $this->expectException(InvalidPropertyException::class);

        new class(['test' => 'test']) implements EntityInterface {
            use EntityTrait;
        };
    }

    public function testEmptyException()
    {
        $this->expectException(EmptyException::class);

        new class([]) implements EntityInterface {
            use EntityTrait;

            private $name;

            private function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([
                    new Definition("name", TypeInterface::TYPE_STRING, false),
                ]);
            }
        };
    }

    public function testSetException()
    {
        $this->expectException(\BadMethodCallException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;
        };

        $mock->test = 1;
    }

    public function testUnsetException()
    {
        $this->expectException(\BadMethodCallException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;
        };

        unset($mock->test);
    }

    public function testInvalidGetException()
    {
        $this->expectException(InvalidPropertyException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;
        };

        $mock->test;
    }

    public function testInvalidWithException()
    {
        $this->expectException(InvalidPropertyException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;
        };

        $mock->with("test", "test");
    }
}
