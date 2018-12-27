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
