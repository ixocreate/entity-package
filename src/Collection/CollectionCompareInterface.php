<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Collection;

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
