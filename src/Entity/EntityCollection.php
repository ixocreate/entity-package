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
namespace KiwiSuite\Entity\Entity;

use KiwiSuite\Entity\Collection\AbstractCollection;

class EntityCollection extends AbstractCollection
{
    public function __construct(array $items = [], $callbackKeys = null)
    {
        $items = \array_values($items);
        $items = (function (EntityInterface ...$entity) {
            return $entity;
        })(...$items);

        parent::__construct($items, $callbackKeys);
    }
}
