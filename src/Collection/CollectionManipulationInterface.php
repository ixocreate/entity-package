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

interface CollectionManipulationInterface
{
    /**
     * Removes and returns the last collection item
     *
     * @return mixed
     */
    public function pop();

    /**
     * Removes and returns the first collection item
     *
     * @return mixed
     */
    public function shift();

    /**
     * Removes and returns all items filtered by a given callable
     *
     * @param callable $callable
     * @return CollectionInterface
     */
    public function pull(callable $callable): CollectionInterface;

    /**
     * Adds an item at the beginning of the current collection
     *
     * @param mixed $item
     */
    public function prepend($item): void;

    /**
     * Adds an item at the end of the current collection
     *
     * @param mixed $item
     */
    public function push($item): void;
}
