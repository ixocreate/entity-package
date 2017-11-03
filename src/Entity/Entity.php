<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Entity\Entity;

use KiwiSuite\Entity\Exception\EmptyException;
use KiwiSuite\Entity\Exception\InvalidPropertyException;
use KiwiSuite\Entity\Helper\DefinitionName;
use KiwiSuite\Entity\Type\Type;

class Entity implements EntityInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var DefinitionCollection
     */
    private $definitionCollection;

    /**
     * @var array
     */
    private $markedOptional = [];

    /**
     * Entity constructor.
     * @param array $data
     * @param DefinitionCollection $definitionCollection
     */
    public function __construct(array $data, DefinitionCollection $definitionCollection)
    {
        $this->definitionCollection = $definitionCollection;
        $this->applyData($data);
    }

    /**
     * @param array $data
     */
    private function applyData(array $data): void
    {
        foreach ($data as $name => $value) {
            $name = DefinitionName::filter($name);

            if (!$this->definitionCollection->has($name)) {
                throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
            }

            $this->setValue($name, $value);
        }

        /** @var Definition $definition */
        foreach ($this->definitionCollection as $definition) {
            if ($definition->isOptional() && !\array_key_exists($definition->getName(), $this->data)) {
                $this->markedOptional[] = $definition->getName();
                continue;
            }
            if (!$definition->isNullAble() && !\array_key_exists($definition->getName(), $this->data)) {
                throw new EmptyException(\sprintf("Not nullable property '%s' not set", $definition->getName()));
            }
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    private function setValue(string $name, $value): void
    {
        $this->data[$name] = Type::create($value, $this->definitionCollection->get($name)->getType());
    }

    /**
     * @param string $name
     * @param bool $includeOptional
     * @return bool
     */
    public function hasProperty(string $name, bool $includeOptional = false): bool
    {
        $name = DefinitionName::filter($name);
        $hasDefinition = $this->definitionCollection->has($name);

        if (!$hasDefinition || $includeOptional === true) {
            return $hasDefinition;
        }

        /** @var Definition $definition */
        $definition = $this->definitionCollection->get($name);
        if (!$definition->isOptional()) {
            return true;
        }

        if (\in_array($definition->getName(), $this->markedOptional)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        $name = DefinitionName::filter($name);
        if (!$this->hasProperty($name)) {
            throw new InvalidPropertyException(
                \sprintf("Invalid property '%s' in '%s'", $name, \get_class($this))
            );
        }
        return isset($this->data[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $name = DefinitionName::filter($name);
        if (!$this->hasProperty($name)) {
            throw new InvalidPropertyException(
                \sprintf("Invalid property '%s' in '%s'", $name, \get_class($this))
            );
        }
        return $this->data[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        throw new \BadMethodCallException(\sprintf("__set() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        throw new \BadMethodCallException(\sprintf("__unset() is disabled in '%s'", \get_class($this)));
    }

    /**
     * @return DefinitionCollection
     */
    public function getDefinitions(): DefinitionCollection
    {
        return $this->definitionCollection;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Entity
     */
    public function with(string $name, $value): self
    {
        $name = DefinitionName::filter($name);
        if (!$this->definitionCollection->has($name, true)) {
            throw new InvalidPropertyException(\sprintf("Invalid property '%s'", $name));
        }

        $data = $this->data;
        $data[$name] = $value;

        return new self($data, $this->definitionCollection);
    }
}
