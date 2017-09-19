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

interface CollectionCollectionInterface extends CollectionInterface
{
    /**
     * @return array
     */
    public function getCollections(): array;

    /**
     * @return \Iterator
     */
    public function getCollectionIterator(): \Iterator;

    /**
     * @return int
     */
    public function getCollectionCount(): int;
}
