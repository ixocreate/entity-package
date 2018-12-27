<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

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
