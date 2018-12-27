<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

use Ixocreate\Entity\Collection\AbstractCollection;

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
