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

namespace KiwiSuite\Entity\Helper;

use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;

final class DefinitionName
{
    /**
     * @var FilterChain
     */
    private static $filter;

    /**
     * DefinitionName constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @return FilterChain
     */
    private static function getFilter(): FilterChain
    {
        if (self::$filter === null) {
            self::$filter = new FilterChain();
            self::$filter->attach(new UnderscoreToCamelCase());
            self::$filter->attach(new DashToCamelCase());
            self::$filter->attach(new SeparatorToCamelCase(":"));
        }

        return self::$filter;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function filter(string $name): string
    {
        return \lcfirst(self::getFilter()->filter($name));
    }
}
