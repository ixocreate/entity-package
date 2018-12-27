<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Entity\Collection;

use Ixocreate\Entity\Entity\Definition;
use Ixocreate\Entity\Entity\DefinitionCollection;
use Ixocreate\Entity\Entity\EntityCollection;
use Ixocreate\Entity\Entity\EntityInterface;
use Ixocreate\Entity\Entity\EntityTrait;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    public function testEntityCollection()
    {
        $data = [
            'name' => 'test',
        ];
        $entity = new class($data) implements EntityInterface {
            use EntityTrait;

            private $name;

            protected static function createDefinitions() : DefinitionCollection
            {
                return new DefinitionCollection([
                      new Definition("name", "string", false),
                  ]);
            }
        };

        $entityCollection = new EntityCollection([$entity]);

        $this->assertSame(1, $entityCollection->count());
        $this->assertSame("test", $entityCollection->get(0)->name);
    }

    public function testDataIntegrityInvalidDataException()
    {
        $this->expectException(\Throwable::class);
        new EntityCollection(['id' => 1]);
    }
}
