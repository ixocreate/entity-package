<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Collection;

use Ixocreate\Entity\Exception\EmptyException;

interface CollectionAggregateInterface
{
    /**
     * Returns the average of a given selector
     *
     * @param callable|string|int $selector
     * @throws EmptyException
     * @return float
     */
    public function avg($selector): float;

    /**
     * Returns the sum of a given selector
     *
     * @param callable|string|int $selector
     * @return float
     */
    public function sum($selector): float;

    /**
     * Returns the minimum value of a given selector
     *
     * @param callable|string|int $selector
     * @throws EmptyException
     * @return CollectionInterface
     */
    public function min($selector): CollectionInterface;

    /**
     * Returns the maximum value of a given selector
     *
     * @param callable|string|int $selector
     * @throws EmptyException
     * @return CollectionInterface
     */
    public function max($selector): CollectionInterface;

    /**
     * Iteratively reduce the array to a single value using a callback function
     *
     * @param callable $callable
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callable, $initial = null);
}
