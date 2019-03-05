<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Entity\Collection;

use Ixocreate\Collection\Exception\InvalidType;
use Ixocreate\Entity\Entity\Definition;
use Ixocreate\Entity\Entity\DefinitionCollection;
use Ixocreate\Entity\Entity\EntityCollection;
use Ixocreate\Entity\Entity\EntityInterface;
use Ixocreate\Entity\Entity\EntityTrait;
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
        return new class($data) implements EntityInterface
        {
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

        $collection = new EntityCollection($data, function(EntityInterface $entity) {
            return $entity['id'];
        });

        var_dump($collection->toArray());

        //$this->assertSame("Test One", $collection->get('One')->name);
        ////$this->assertSame($data, $collection->values()->toArray());
    }

    public function testInvalidTypeException()
    {
        $this->expectException(InvalidType::class);
        (new EntityCollection([['id' => 1]]))->toArray();
    }
}
