<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @see https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Entity\Type;

interface TypeInterface
{
    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_ARRAY = 'array';
    const TYPE_CALLABLE = 'callable';

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public static function getInternalType() : string;

    /**
     * @param $value
     * @return mixed
     */
    public static function convertToInternalType($value);
}
