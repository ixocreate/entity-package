<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity;

use Ixocreate\Collection\AbstractCollection;
use Ixocreate\Collection\Collection;
use Ixocreate\Collection\Exception\InvalidType;

final class DefinitionCollection extends AbstractCollection
{
    public function __construct($items = [])
    {
        $items = new Collection($items);

        /**
         * add type check
         */
        $items = $items->each(function ($value) {
            if (!($value instanceof Definition)) {
                throw new InvalidType('All items must be of type ' . Definition::class . '. Got item of type ' . \gettype($value));
            }
        });

        /**
         * index by name after type check
         */
        $items = $items->indexBy(function (Definition $definition) {
            return $definition->getName();
        });

        return parent::__construct($items);
    }
}
