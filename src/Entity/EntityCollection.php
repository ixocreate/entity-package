<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

use Ixocreate\Collection\Collection;

final class EntityCollection extends Collection
{
    /**
     * EntityCollection constructor.
     * @param array $items
     * @param callable|string|int|null $indexBy
     */
    public function __construct(array $items = [], $indexBy = null)
    {
        $items = \array_values($items);
        $items = (function (EntityInterface ...$entity) {
            return $entity;
        })(...$items);

        parent::__construct($items, $indexBy);
    }
}
