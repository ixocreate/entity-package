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

interface CollectionItemSelectorInterface
{
    /**
     * Returns an array of all values of a given selector
     *
     * @param callable|string|int $selector
     * @return array
     */
    public function parts($selector): array;

    /**
     * Returns one collection item based on a given key
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Checks if an collection item exists based on a given selector
     *
     * @param string|int $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Returns one random collection item
     *
     * @return mixed
     */
    public function random();

    /**
     * Returns the first collection item or the first collection item matched by a given callable
     *
     * @param callable|null $callable
     * @return mixed
     */
    public function first(callable $callable = null);

    /**
     * Returns the last collection item or the last collection item matched by a given callable
     *
     * @param callable|null $callable
     * @return mixed
     */
    public function last(callable $callable = null);
}
