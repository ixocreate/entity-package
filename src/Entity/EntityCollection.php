<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Package\Entity;

use Ixocreate\Collection\AbstractCollection;
use Ixocreate\Collection\Collection;
use Ixocreate\Collection\Exception\InvalidType;
use Traversable;

final class EntityCollection extends AbstractCollection
{
    /**
     * @param callable|array|Traversable $items
     * @param callable|string|int|null $indexBy
     */
    public function __construct($items = [], $indexBy = null)
    {
        $items = new Collection($items);

        /**
         * add type check
         */
        $items = $items->each(function ($value) {
            if (!($value instanceof EntityInterface)) {
                throw new InvalidType('All items must be of type ' . EntityInterface::class . '. Got item of type ' . \gettype($value));
            }
        });

        /**
         * index by after type check
         */
        if ($indexBy !== null) {
            $items = $items->indexBy($indexBy);
        }

        parent::__construct($items);
    }
}
