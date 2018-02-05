<?php
namespace KiwiSuiteMisc\Entity;

use KiwiSuite\Entity\Type\Convert\Convert;
use KiwiSuite\Entity\Type\TypeInterface;

final class MockType implements TypeInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * TestType constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public static function getInternalType(): string
    {
        return TypeInterface::TYPE_STRING;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function convertToInternalType($value)
    {
        return Convert::convertString($value);
    }
}
