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
namespace KiwiSuite\Entity\Collection;

interface CollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * Returns all collection items
     *
     * @return array
     */
    public function all(): array;

    /**
     * Returns all keys of the collection items
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Executes a callable over each collection item
     *
     * @param callable $callable
     */
    public function each(callable $callable): void;

    /**
     * Checks if the current collection is empty
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
