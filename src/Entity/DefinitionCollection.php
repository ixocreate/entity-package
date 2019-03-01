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

final class DefinitionCollection extends AbstractCollection
{
    public function __construct($items = [])
    {
        return parent::__construct(
            new Collection(
                (function (Definition ...$item) {
                    return $item;
                })(...$items),
                function (Definition $definition) {
                    return $definition->getName();
                }
            )
        );
    }
}
