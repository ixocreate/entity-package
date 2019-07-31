<?php
declare(strict_types=1);

namespace Ixocreate\Benchmark\Cms;

use Ixocreate\Entity\Definition;
use Ixocreate\Entity\DefinitionCollection;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Entity\EntityTrait;
use Ixocreate\Schema\Type\DateTimeType;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\Schema\Type\UuidType;

final class TestEntity implements EntityInterface
{
    use EntityTrait;

    private $id;

    private $name;

    private $updatedAt;

    private $createdAt;

    public function id(): UuidType
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name();
    }

    public function updatedAt(): DateTimeType
    {
        return $this->updatedAt;
    }

    public function createdAt(): DateTimeType
    {
        return $this->createdAt;
    }

    /**
     * @return DefinitionCollection
     */
    protected static function createDefinitions(): DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition('id', UuidType::serviceName()),
            new Definition('name', TypeInterface::TYPE_STRING),
            new Definition('updatedAt', DateTimeType::serviceName()),
            new Definition('createdAt', DateTimeType::serviceName()),
        ]);
    }
}
