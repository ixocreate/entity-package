<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

use Ixocreate\Collection\AbstractCollection;
use Ixocreate\Collection\Collection;
use Traversable;

final class EntityCollection extends AbstractCollection
{
    /**
     * @param callable|array|Traversable $items
     * @param callable|string|int|null $indexBy
     */
    public function __construct($items = [], $indexBy = null)
    {
        parent::__construct(
            new Collection(
                (function (EntityInterface ...$entity) {
                    return $entity;
                })(...$items),
                $indexBy
            )
        );
    }
}
