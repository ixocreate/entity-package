<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Entity;

use Ixocreate\Entity\Type\AbstractType;

final class MockType extends AbstractType
{
    public static function serviceName(): string
    {
        return 'mock';
    }
}
