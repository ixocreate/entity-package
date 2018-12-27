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

use Ixocreate\Contract\Type\TypeInterface;
use Ixocreate\Entity\Entity\Definition;
use Ixocreate\Entity\Entity\DefinitionCollection;
use Ixocreate\Entity\Entity\EntityInterface;
use Ixocreate\Entity\Entity\EntityTrait;
use Ixocreate\Entity\Exception\EmptyException;
use Ixocreate\Entity\Exception\InvalidPropertyException;
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

            protected static function createDefinitions() : DefinitionCollection
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

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([]);
            }
        };
    }

    public function testEmptyException()
    {
        $this->expectException(EmptyException::class);

        new class([]) implements EntityInterface {
            use EntityTrait;

            private $name;

            protected static function createDefinitions() : DefinitionCollection
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

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([]);
            }
        };

        $mock->test = 1;
    }

    public function testUnsetException()
    {
        $this->expectException(\BadMethodCallException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([]);
            }
        };

        unset($mock->test);
    }

    public function testInvalidGetException()
    {
        $this->expectException(InvalidPropertyException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([]);
            }
        };

        $mock->test;
    }

    public function testInvalidWithException()
    {
        $this->expectException(InvalidPropertyException::class);

        $mock = new class([]) implements EntityInterface {
            use EntityTrait;

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([]);
            }
        };

        $mock->with("test", "test");
    }
}
