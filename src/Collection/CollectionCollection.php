<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Package\Collection;

use Ixocreate\Collection\CollectionInterface;

/**
 * @package Ixocreate\Entity\Package\Collection
 * @deprecated
 * @see \Ixocreate\Collection\CollectionCollection
 */
final class CollectionCollection extends \Ixocreate\Collection\AbstractCollection
{
    public function __construct($items = [])
    {
        parent::__construct(
            (function (CollectionInterface ...$collection) {
                return $collection;
            })(...$items)
        );
    }
}
