<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity\Collection;

use Ixocreate\Collection\Exception\InvalidType;
use Ixocreate\Entity\Definition;
use Ixocreate\Entity\DefinitionCollection;
use Ixocreate\Entity\EntityCollection;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Entity\EntityTrait;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    private function data()
    {
        return [
            $this->entity(['id' => 'One', 'name' => 'Test One']),
            $this->entity(['id' => 'Two', 'name' => 'Test Two']),
            $this->entity(['id' => 'Three', 'name' => 'Test Three']),
            $this->entity(['id' => 'Four', 'name' => 'Test Four']),
            $this->entity(['id' => 'Five', 'name' => 'Test Five']),
        ];
    }

    private function entity($data)
    {
        return new class($data) implements EntityInterface {
            use EntityTrait;

            private $id;

            private $name;

            protected static function createDefinitions(): DefinitionCollection
            {
                return new DefinitionCollection([
                    new Definition("id", "string", false),
                    new Definition("name", "string", false),
                ]);
            }
        };
    }

    public function testCollection()
    {
        $data = $this->data();
        $collection = new EntityCollection($data);

        $this->assertSame(5, $collection->count());
        $this->assertSame('Test One', $collection->get(0)->name);
        $this->assertSame($data, $collection->toArray());
    }

    public function testIndexBy()
    {
        $data = $this->data();
        $collection = new EntityCollection($data, 'id');

        $indexData = [
            $data[0]->id => $data[0],
            $data[1]->id => $data[1],
            $data[2]->id => $data[2],
            $data[3]->id => $data[3],
            $data[4]->id => $data[4],
        ];

        $this->assertSame(5, $collection->count());
        $this->assertSame($indexData, $collection->toArray());
    }

    public function testInvalidTypeException()
    {
        $this->expectException(InvalidType::class);
        (new EntityCollection([['id' => 1]]))->toArray();
    }
}
