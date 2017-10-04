<?php
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
}
