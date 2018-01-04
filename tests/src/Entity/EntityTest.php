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
use KiwiSuite\Entity\Exception\EmptyException;
use KiwiSuite\Entity\Exception\InvalidPropertyException;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testConstructor()
    {
        $data = [
            "stringTest" => "a string to test ...",
        ];
        $entity = new Entity(
            $data,
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        $this->assertEquals($data, $entity->toArray());

        $entity = new Entity(
            [
                "string_test" => "a string to test ...",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        $this->assertEquals($data, $entity->toArray());

        $entity = new Entity(
            [
                "stringTest" => "a string to test ...",
            ],
            new DefinitionCollection([
                new Definition("string_test", "string"),
            ])
        );

        $this->assertEquals($data, $entity->toArray());
    }

    public function testMissingProperty()
    {
        $this->expectException(EmptyException::class);

        new Entity(
            [],
            new DefinitionCollection([
                new Definition("string_test", "string", false),
            ])
        );
    }

    public function testInvalidProperty()
    {
        $this->expectException(InvalidPropertyException::class);

        new Entity(
            [
                'invalid_prop' => 'test',
            ],
            new DefinitionCollection([
                new Definition("string_test", "string", false),
            ])
        );
    }

    public function testHasProperty()
    {
        $entity = new Entity(
            [],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
                new Definition("optional", "string", true, false, true),
                new Definition("optionalNullAble", "string", false, false, true),
            ])
        );

        $this->assertTrue($entity->hasProperty("stringTest"));
        $this->assertTrue($entity->hasProperty("string_test"));
        $this->assertFalse($entity->hasProperty("missing_prop"));

        $this->assertFalse($entity->hasProperty("optional"));
        $this->assertTrue($entity->hasProperty("optional", true));

        $this->assertFalse($entity->hasProperty("optionalNullAble"));
        $this->assertTrue($entity->hasProperty("optionalNullAble", true));
    }

    public function testGet()
    {
        $entity = new Entity(
            [
                'stringTest' => "string",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        $this->assertEquals("string", $entity->stringTest);
        $this->assertEquals("string", $entity->string_test);
    }

    public function testGetInvalidProperty()
    {
        $this->expectException(InvalidPropertyException::class);

        $entity = new Entity(
            [
                'string_test' => 'test',
            ],
            new DefinitionCollection([
                new Definition("string_test", "string", false),
            ])
        );

        $entity->invalidProperty;
    }

    public function testIsset()
    {
        $entity = new Entity(
            [
                'stringTest' => "string",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        $this->assertTrue(isset($entity->stringTest));
        $this->assertTrue(isset($entity->string_test));
    }

    public function testIssetInvalidProperty()
    {
        $this->expectException(InvalidPropertyException::class);

        $entity = new Entity(
            [
                'string_test' => 'test',
            ],
            new DefinitionCollection([
                new Definition("string_test", "string", false),
            ])
        );

        isset($entity->invalidProperty);
    }

    public function testGetDefinitions()
    {
        $definitionCollection = new DefinitionCollection([
            new Definition("string_test", "string", false),
        ]);

        $entity = new Entity(
            [
                'string_test' => 'test',
            ],
            $definitionCollection
        );

        $this->assertEquals($definitionCollection, $entity->getDefinitions());
    }

    public function testWith()
    {
        $entity = new Entity(
            [
                'stringTest' => "string",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
                new Definition("optional", "string", true, true, true),
            ])
        );
        $entity = $entity->with("stringTest", "newString");
        $this->assertEquals("newString", $entity->stringTest);

        $entity = $entity->with("string_test", "anotherString");
        $this->assertEquals("anotherString", $entity->stringTest);

        $entity = $entity->with("optional", "optionalProperty");
        $this->assertEquals("optionalProperty", $entity->optional);
    }

    public function testWithInvalidProperty()
    {
        $this->expectException(InvalidPropertyException::class);

        $entity = new Entity(
            [
                'string_test' => 'test',
            ],
            new DefinitionCollection([
                new Definition("string_test", "string", false),
            ])
        );
        $entity->with("invalidProp", "test");
    }

    public function testSetException()
    {
        $this->expectException(\BadMethodCallException::class);

        $entity = new Entity(
            [
                'stringTest' => "string",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        $entity->stringTest = "1";
    }

    public function testUnsetException()
    {
        $this->expectException(\BadMethodCallException::class);

        $entity = new Entity(
            [
                'stringTest' => "string",
            ],
            new DefinitionCollection([
                new Definition("stringTest", "string"),
            ])
        );

        unset($entity->stringTest);
    }
}
