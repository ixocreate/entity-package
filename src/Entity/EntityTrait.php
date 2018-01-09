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
namespace KiwiSuite\Entity\Entity;

use KiwiSuite\Entity\Exception\EmptyException;
use KiwiSuite\Entity\Exception\InvalidPropertyException;
use KiwiSuite\Entity\Type\Type;

trait EntityTrait
{
    /**
     * @var DefinitionCollection
     */
    private $definitionCollection;

    public function __construct(array $data)
    {
        $this->applyData($data);
    }

    /**
     * @return DefinitionCollection
     */
    public function getDefinitions() : DefinitionCollection
    {
        if ($this->definitionCollection === null) {
            $this->definitionCollection = $this->createDefinitions();
        }

        return $this->definitionCollection;
    }

    /**
     * @return DefinitionCollection
     * @codeCoverageIgnore
     */
    private function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([]);
    }

    /**
     * @param array $data
     */
    private function applyData(array $data) : void
    {
        $variables = [];

        foreach ($data as $name => $value) {
            if (!$this->getDefinitions()->has($name)) {
                throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
            }

            $variables[] = $name;

            $this->setValue($name, $value);
        }

        /** @var Definition $definition */
        foreach ($this->getDefinitions() as $definition) {
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
        if ($value === null && $this->getDefinitions()->get($name)->isNullAble()) {
            $this->{$name} = null;
            return;
        }
        $this->{$name} = Type::create($value, $this->getDefinitions()->get($name)->getType());
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
        if (!$this->getDefinitions()->has($name)) {
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
        throw new \BadMethodCallException(\sprintf("__set() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @param string $name
     */
    public function __unset(string $name) : void
    {
        throw new \BadMethodCallException(\sprintf("__unset() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [];
        foreach ($this->getDefinitions() as $definition) {
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
        if (!$this->getDefinitions()->has($name)) {
            throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
        }

        $data = $this->toArray();
        $data[$name] = $value;

        return new static($data);
    }
}
