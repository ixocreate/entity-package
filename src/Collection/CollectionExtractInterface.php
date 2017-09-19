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
namespace KiwiSuite\Entity\Collection;

interface CollectionExtractInterface
{
    /**
     * Filter the collection by a given callable and return the result as new collection
     *
     * @param callable $callable
     * @return CollectionInterface
     */
    public function filter(callable $callable): CollectionInterface;

    /**
     * Sorts the current collection based on a given callable
     *
     * @param callable $callable
     * @return CollectionInterface
     */
    public function sort(callable $callable): CollectionInterface;

    /**
     * Merge another collection into the current collection and returns as new collection
     *
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function merge(CollectionInterface $collection): CollectionInterface;

    /**
     * Chunk the collection items and return them as a collection
     *
     * @param int $size
     * @return CollectionCollectionInterface
     */
    public function chunk(int $size): CollectionCollectionInterface;

    /**
     * Split the collection into groups and return them as a collection
     *
     * @param int $groups
     * @return CollectionCollectionInterface
     */
    public function split(int $groups): CollectionCollectionInterface;

    /**
     * Return a new collection of every n-th element
     *
     * @param int $step
     * @param int $offset
     * @return CollectionInterface
     */
    public function nth(int $step, $offset = 0): CollectionInterface;


    /**
     * Shuffles and returns collection items
     *
     * @return CollectionInterface
     */
    public function shuffle(): CollectionInterface;

    /**
     * Returns a sequence of collection items as collection interface specified by offset and length
     *
     * @param int $offset
     * @param int|null $length
     * @return CollectionInterface
     */
    public function slice(int $offset, int $length = null): CollectionInterface;

    /**
     * Returns a collection in reverse order
     *
     * @return CollectionInterface
     */
    public function reverse(): CollectionInterface;
}
