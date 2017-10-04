<?php
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
