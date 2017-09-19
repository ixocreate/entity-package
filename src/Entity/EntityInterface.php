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

interface EntityInterface
{
    /**
     * @return DefinitionCollection
     */
    public function getDefinitions(): DefinitionCollection;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void;

    /**
     * @param string $name
     */
    public function __unset(string $name): void;

    /**
     * @param string $name
     * @param mixed $value
     * @return EntityInterface
     */
    public function with(string $name, $value);

    /**
     * @return array
     */
    public function toArray(): array;
}
