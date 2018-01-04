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
namespace KiwiSuiteTest\Entity\Collection;

use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\Entity;
use KiwiSuite\Entity\Entity\EntityCollection;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    public function testEntityCollection()
    {
        $entity = new Entity(
            [
                'name' => 'test',
            ],
            new DefinitionCollection([
                new Definition("name", "string", false),
            ])
        );

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
