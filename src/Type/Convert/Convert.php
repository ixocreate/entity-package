<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Entity\Type\Convert;

class Convert
{
    public static function convertString($value)
    {
        if (\is_scalar($value)) {
            return (string) $value;
        }

        return $value;
    }

    public static function convertBool($value)
    {
        if ($value === "0" || $value === "1" || $value === 1 || $value === 0) {
            return (bool) $value;
        }

        return $value;
    }

    public static function convertFloat($value)
    {
        if (\is_numeric($value)) {
            return (float) $value;
        }

        return $value;
    }

    public static function convertInt($value)
    {
        if (\is_numeric($value)) {
            return (int) $value;
        }

        return $value;
    }
}
