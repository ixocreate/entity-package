<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

use Ixocreate\Entity\Exception\EmptyException;
use Ixocreate\Entity\Exception\InvalidPropertyException;
use Ixocreate\Entity\Exception\InvalidTypeException;
use Ixocreate\Entity\Type\Type;

trait EntityTrait
{
    /**
     * @var DefinitionCollection
     */
    private static $definitionCollection;

    public function __construct(array $data)
    {
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

    /**
     * @param array $data
     */
    private function applyData(array $data) : void
    {
        $variables = [];

        foreach ($data as $name => $value) {
            if (!self::getDefinitions()->has($name)) {
                throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
            }

            $variables[] = $name;

            try {
                $this->setValue($name, $value);
            }
            catch (InvalidTypeException $exception) {
                throw new InvalidTypeException(\sprintf("Exception when setting value for '%s': ", $name) . $exception->getMessage());
            }
        }

        /** @var Definition $definition */
        foreach (self::getDefinitions() as $definition) {
            if (\in_array($definition->getName(), $variables)) {
                continue;
            }

            $name = $definition->getName();
            if ($definition->hasDefault()) {
                $this->setValue($name, $definition->getDefault());
                continue;
            }

            if ($definition->isNullAble()) {
                $this->setValue($name, null);
                continue;
            }

            throw new EmptyException(\sprintf("Property '%s' not set", $definition->getName()));
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    private function setValue(string $name, $value) : void
    {
        if ($value === null && self::getDefinitions()->get($name)->isNullAble()) {
            $this->{$name} = null;
            return;
        }
        $this->{$name} = Type::create(
            $value,
            self::getDefinitions()->get($name)->getType(),
            self::getDefinitions()->get($name)->getOptions()
        );
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
            throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
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
}
