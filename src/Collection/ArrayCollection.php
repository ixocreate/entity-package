<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Package\Collection;

use Traversable;

/**
 * @package Ixocreate\Entity\Package\Collection
 * @deprecated
 * @see \Ixocreate\Collection\ArrayCollection
 */
final class ArrayCollection extends \Ixocreate\Collection\AbstractCollection
{
    /**
     * @param callable|array|Traversable $items
     * @param callable|string|int|null $indexBy
     */
    public function __construct($items = [], $indexBy = null)
    {
        return parent::__construct(
            new Collection(
                (function (array ...$item) {
                    return $item;
                })(...$items),
                $indexBy
            )
        );
    }
}
