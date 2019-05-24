<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity\Entity;

use Ixocreate\Collection\Exception\InvalidType;
use Ixocreate\Entity\Definition;
use Ixocreate\Entity\DefinitionCollection;
use PHPUnit\Framework\TestCase;

class DefinitionCollectionTest extends TestCase
{
    private function data()
    {
        return [
            new Definition(
                "testString",
                "string",
                false,
                false
            ),
            new Definition(
                "testString2",
                "string",
                false,
                true
            ),
        ];
    }

    public function testCollection()
    {
        $data = $this->data();
        $collection = new DefinitionCollection($data);
        $this->assertCount(2, $collection);

        $data = $collection->toArray();
        $collection = new DefinitionCollection($collection);
        $this->assertSame($data, $collection->toArray());
    }

    public function testIsIndexedByName()
    {
        $collection = new DefinitionCollection($this->data());
        $this->assertEquals($this->data()[1], $collection->get("testString2"));
    }

    public function testInvalidTypeException()
    {
        $this->expectException(InvalidType::class);
        (new DefinitionCollection([['id' => 1]]))->toArray();
    }
}
