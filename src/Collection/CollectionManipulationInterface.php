<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Collection;

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
