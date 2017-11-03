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
namespace KiwiSuite\Entity\Type\Type;

use KiwiSuite\Entity\Type\TypeInterface;

final class Email implements TypeInterface
{
    private $email;

    public function __construct(string $value)
    {
        $this->email = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
