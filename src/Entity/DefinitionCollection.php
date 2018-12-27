<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Entity;

use Ixocreate\Entity\Collection\AbstractCollection;

final class DefinitionCollection extends AbstractCollection
{
    public function __construct(array $items = [])
    {
        $items = (function (Definition ...$model) {
            return $model;
        })(...$items);

        parent::__construct(
            $items,
            function (Definition $definition) {
                return $definition->getName();
            }
        );
    }
}
