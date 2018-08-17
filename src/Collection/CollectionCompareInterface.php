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
namespace KiwiSuite\Entity\Collection;

interface CollectionCompareInterface
{
    /**
     * Return a collection of items which are different between the given collection and the current collection
     *
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function diff(CollectionInterface $collection): CollectionInterface;

    /**
     * Return a collection of intersecting collection items
     *
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function intersect(CollectionInterface $collection): CollectionInterface;
}
