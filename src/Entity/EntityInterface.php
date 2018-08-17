<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @link https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Entity\Entity;

interface EntityInterface extends \JsonSerializable, \ArrayAccess
{
    /**
     * @return DefinitionCollection
     */
    public static function getDefinitions(): DefinitionCollection;

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

    /**
     * @return array
     */
    public function toPublicArray(): array;
}
