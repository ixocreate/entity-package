<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity;

use Ixocreate\Entity\Exception\InvalidPropertyException;
use Ixocreate\Entity\Exception\PropertyNotFoundException;
use Ixocreate\Schema\Builder\BuilderInterface;
use Ixocreate\Schema\Element\ElementInterface;
use Ixocreate\Schema\Element\ElementProviderInterface;
use Ixocreate\Schema\Element\TextElement;
use Ixocreate\Schema\Schema;
use Ixocreate\Schema\SchemaInterface;
use Ixocreate\Schema\Type\Exception\InvalidTypeException;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Schema\Type\TypeInterface;

trait EntityTrait
{
    /**
     * @var DefinitionCollection
     */
    private static $definitionCollection;

    /**
     * @var null|TypeInterface[]
     */
    private static $prototypes = null;

    /**
     * EntityTrait constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->prepare();
        $this->applyData($data);
    }

    /**
     * @return DefinitionCollection
     */
    public static function getDefinitions() : DefinitionCollection
    {
        if (self::$definitionCollection === null) {
            self::$definitionCollection = self::createDefinitions();
        }

        return self::$definitionCollection;
    }

    /**
     * @return DefinitionCollection
     */
    abstract protected static function createDefinitions() : DefinitionCollection;

    private function prepare(): void
    {
        if (self::$prototypes !== null) {
            return;
        }

        self::$prototypes = [];
        /** @var Definition $definition */
        foreach (self::getDefinitions() as $definition) {
            if (Type::isPhpType($definition->getType())) {
                self::$prototypes[$definition->getName()] = [
                    'type' => 'internal',
                ];
                continue;
            }

            self::$prototypes[$definition->getName()] = [
                'type' => 'typeInterface',
                'value' => Type::get($definition->getType()),
            ];
        }
    }

    /**
     * @param array $data
     */
    private function applyData(array $data) : void
    {
        /** @var Definition $definition */
        foreach (self::getDefinitions()->toArray() as $definition) {
            if (\array_key_exists($definition->getName(), $data)) {
                try {
                    $this->applyValue($definition, $data[$definition->getName()]);
                } catch (InvalidTypeException $exception) {
                    throw new InvalidTypeException(\sprintf("Invalid value type for '%s' in '%s': ", $definition->getName(), \get_class($this)) . $exception->getMessage());
                }

                unset($data[$definition->getName()]);

                continue;
            }

            if ($definition->hasDefault()) {
                $this->applyValue($definition, $definition->getDefault());
                continue;
            }

            if ($definition->isNullAble()) {
                $this->applyValue($definition, null);
                continue;
            }

            throw new PropertyNotFoundException(\sprintf("Property '%s' not found in '%s'", $definition->getName(), \get_class($this)));
        }
        if (!empty($data)) {
            foreach (\array_keys($data) as $name) {
                throw new InvalidPropertyException(\sprintf("Invalid property '%s' in '%s'", $name, \get_class($this)));
            }
        }
    }

    private function applyValue(Definition $definition, $value): void
    {
        $name = $definition->getName();

        if ($value === null && $definition->isNullAble()) {
            $this->{$name} = null;
            return;
        }

        if (self::$prototypes[$name]['type'] === 'internal') {
            Type::checkPhpType($value, $definition->getType());
            $this->{$name} = $value;
            return;
        }

        $typeObject = self::$prototypes[$name]['value'];

        if ($value instanceof $typeObject) {
            $this->{$name} = $value;
            return;
        }

        $this->{$name} = $typeObject->create($value, $definition->getOptions());
    }

    /**
     * @param string $name
     * @param mixed $value
     * @deprecated use applyValue
     */
    private function setValue(string $name, $value) : void
    {
        $definition = self::getDefinitions()->get($name);
        $this->applyValue($definition, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return isset($this->{$name});
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!self::getDefinitions()->has($name)) {
            throw new InvalidPropertyException(
                \sprintf("Invalid property '%s' in '%s'", $name, \get_class($this))
            );
        }

        return $this->{$name};
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value) : void
    {
        throw new \BadMethodCallException(\sprintf("Cannot set '%s'. __set() is disabled in '%s'", $name, \get_class($this)));
    }

    /**
     * @param string $name
     */
    public function __unset(string $name) : void
    {
        throw new \BadMethodCallException(\sprintf("Cannot unset '%s'. __unset() is disabled in '%s'", $name, \get_class($this)));
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [];
        foreach (self::getDefinitions() as $definition) {
            $name = $definition->getName();
            $data[$name] = $this->{$name};
        }
        return $data;
    }

    /**
     * @return array
     */
    public function toPublicArray(): array
    {
        $data = [];
        foreach (self::getDefinitions() as $definition) {
            if (!$definition->isPublic()) {
                continue;
            }
            $name = $definition->getName();
            $data[$name] = $this->{$name};
        }
        return $data;
    }

    /**
     * @param string $name
     * @param $value
     * @return EntityInterface
     */
    public function with(string $name, $value) : EntityInterface
    {
        if (!self::getDefinitions()->has($name)) {
            throw new InvalidPropertyException(\sprintf("Invalid property '%s' in '%s'", $name, \get_class($this)));
        }

        $data = $this->toArray();
        $data[$name] = $value;

        return new static($data);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toPublicArray();
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!self::getDefinitions()->has($offset)) {
            throw new InvalidPropertyException(
                \sprintf("Invalid property '%s' in '%s'", $offset, \get_class($this))
            );
        }

        return $this->{$offset};
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(\sprintf("offsetSet() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(\sprintf("offsetUnset() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @param BuilderInterface $builder
     * @return SchemaInterface
     */
    public function schema(BuilderInterface $builder): SchemaInterface
    {
        $schema = new Schema();
        /** @var Definition $definition */
        foreach (self::getDefinitions() as $definition) {
            if (\in_array($definition->getName(), ['id', 'createdAt', 'updatedAt'])) {
                continue;
            }

            if (!Type::isPhpType($definition->getType())) {
                $type = Type::get($definition->getType());
                if ($type instanceof ElementProviderInterface) {
                    /** @var ElementInterface $element */
                    $element = $type->provideElement($builder);
                    $element = $element->withName($definition->getName())
                        ->withLabel(\ucfirst($definition->getName()));

                    $schema = $schema->withAddedElement($element);

                    continue;
                }
            }

            /** @var ElementInterface $element */
            $element = $builder->create(TextElement::class, $definition->getName());
            $element = $element->withLabel(\ucfirst($definition->getName()));

            $schema = $schema->withAddedElement($element);
        }

        return $schema;
    }
}
