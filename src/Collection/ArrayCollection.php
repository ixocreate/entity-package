<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Collection;

class ArrayCollection extends AbstractCollection
{
    public function __construct(array $items = [], $callbackKeys = null)
    {
        $items = (function (array ...$array) {
            return $array;
        })(...$items);

        parent::__construct($items, $callbackKeys);
    }
}
