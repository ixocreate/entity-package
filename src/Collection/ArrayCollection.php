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
