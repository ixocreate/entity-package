<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity\Entity;

use Ixocreate\Entity\Definition;
use Ixocreate\Entity\DefinitionCollection;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Entity\EntityTrait;
use Ixocreate\Entity\Exception\InvalidPropertyException;
use Ixocreate\Entity\Exception\PropertyNotFoundException;
use Ixocreate\Schema\Builder\BuilderInterface;
use Ixocreate\Schema\Element\ColorElement;
use Ixocreate\Schema\Element\TextElement;
use Ixocreate\Schema\Type\ColorType;
use Ixocreate\Schema\Type\DateType;
use Ixocreate\Schema\Type\SchemaType;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\ServiceManager\Exception\ServiceNotFoundException;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
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

    /**
     * @runInSeparateProcess
     */
    public function testSchema()
    {
        $typesToRegister = [
            ColorType::class => new ColorType(),
            ColorType::serviceName() => new ColorType(),
        ];

        $reflection = new \ReflectionClass(Type::class);
        $type = $reflection->newInstanceWithoutConstructor();
        $container = (new MockBuilder($this, SubManagerInterface::class))
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
        $container->method('get')->willReturnCallback(function ($requestedName) use ($typesToRegister){
            if (\array_key_exists($requestedName, $typesToRegister)) {
                return $typesToRegister[$requestedName];
            }

            throw new ServiceNotFoundException('Type not found');
        });

        $container->method('has')->willReturnCallback(function ($requestedName) use ($typesToRegister){
            if (\array_key_exists($requestedName, $typesToRegister)) {
                return true;
            }
            return false;
        });
        $reflection = new \ReflectionProperty($type, 'subManager');
        $reflection->setAccessible(true);
        $reflection->setValue($type, $container);
        $reflection = new \ReflectionProperty(Type::class, 'type');
        $reflection->setAccessible(true);
        $reflection->setValue($type);

        $data = [
            'id' => 'id',
            'name' => 'something',
            'color' => '#000000',
        ];

        $mock = new class($data) implements EntityInterface {
            use EntityTrait;

            private $id;

            private $name;

            private $type;

            private $category;

            private $color;

            private $createdAt;

            private $updatedAt;

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([
                    new Definition("id", TypeInterface::TYPE_STRING, false),
                    new Definition("createdAt", TypeInterface::TYPE_STRING, true),
                    new Definition("updatedAt", TypeInterface::TYPE_STRING, true),
                    new Definition("name", TypeInterface::TYPE_STRING, false),
                    new Definition("type", TypeInterface::TYPE_INT, true),
                    new Definition("category", TypeInterface::TYPE_INT, false, true, true, 1),
                    new Definition('color', ColorType::serviceName(), false, true)
                ]);
            }
        };

        $schemaBuilder = $this->createMock(BuilderInterface::class);
        $schemaBuilder->method('create')->willReturnCallback(function (string $element, string $name) {
            if (\in_array($element, [TextElement::class, TextElement::serviceName()])) {
                return (new TextElement())->withName($name);
            }

            return null;
        });
        $schemaBuilder->method('get')->willReturnCallback(function (string $element) {
            if (\in_array($element, [ColorElement::class, ColorElement::serviceName()])) {
                return new ColorElement();
            }

            return null;
        });
        $schema = $mock->schema($schemaBuilder);

        $this->assertTrue($schema->has('name'));
        $this->assertTrue($schema->has('type'));
        $this->assertTrue($schema->has('category'));
        $this->assertTrue($schema->has('color'));
        $this->assertFalse($schema->has('id'));
        $this->assertFalse($schema->has('createdAt'));
        $this->assertFalse($schema->has('updatedAt'));

        $this->assertInstanceOf(TextElement::class, $schema->get('name'));
        $this->assertInstanceOf(TextElement::class, $schema->get('type'));
        $this->assertInstanceOf(TextElement::class, $schema->get('category'));
        $this->assertInstanceOf(ColorElement::class, $schema->get('color'));


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
        $this->expectException(PropertyNotFoundException::class);

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
