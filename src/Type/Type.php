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

use KiwiSuite\Entity\Exception\InvalidTypeException;
use KiwiSuite\Entity\Exception\ServiceNotCreatedException;
use KiwiSuite\Entity\Type\Convert\Convert;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;

final class Type
{
    /**
     * @var Type
     */
    private static $type;

    /**
     * @var SubManagerInterface
     */
    private $subManager;

    /**
     * @param SubManagerInterface $subManager
     */
    private function __construct(SubManagerInterface $subManager = null)
    {
        $this->subManager = $subManager;
    }

    public static function initialize(SubManagerInterface $subManager = null)
    {
        if (self::$type instanceof Type) {
            throw new ServiceNotCreatedException(\sprintf("'%s' already initialized", Type::class));
        }

        self::$type = new Type($subManager);
    }

    /**
     * @return Type
     */
    private static function getInstance(): Type
    {
        if (!(self::$type instanceof Type)) {
            self::initialize();
        }

        return self::$type;
    }

    /**
     * @param $value
     * @param string $type
     * @return mixed
     */
    public static function create($value, string $type)
    {
        return self::getInstance()->doCreate($value, $type);
    }

    /**
     * @param $value
     * @param string $type
     * @return mixed
     */
    private function doCreate($value, string $type)
    {
        $value = $this->convertValue($value, $type);

        if ($this->isPhpType($type)) {
            $functionName = "\is_" . $type;
            if (!$functionName($value)) {
                throw new InvalidTypeException(\sprintf("'%s' is not a '%s'", \gettype($value), $type));
            }

            return $value;
        }

        if ($value instanceof $type) {
            return $value;
        }

        if (!($this->subManager instanceof SubManagerInterface)) {
            throw new ServiceNotCreatedException(\sprintf("'%s' was not initialized with a SubManager", Type::class));
        }

        if (!$this->subManager->has($type)) {
            throw new ServiceNotCreatedException(\sprintf("Can't find type '%s'", $type));
        }

        return $this->subManager->build($type, ['value' => $value]);
    }

    /**
     * @param $type
     * @return bool
     */
    private function isPhpType($type): bool
    {
        return \in_array($type, [TypeInterface::TYPE_STRING, TypeInterface::TYPE_ARRAY, TypeInterface::TYPE_BOOL, TypeInterface::TYPE_CALLABLE, TypeInterface::TYPE_FLOAT, TypeInterface::TYPE_INT]);
    }

    /**
     * @param $value
     * @param string $type
     * @return mixed
     */
    private function convertValue($value, string $type)
    {
        if ($value instanceof $type) {
            return $value;
        }

        if (!$this->isPhpType($type) && \class_exists($type)) {
            $implements = \class_implements($type);
            if ($implements !== false && \in_array(TypeInterface::class, $implements)) {
                $type = \call_user_func($type . '::getInternalType');
            }
        }

        switch ($type) {
            case TypeInterface::TYPE_STRING:
            case TypeInterface::TYPE_BOOL:
            case TypeInterface::TYPE_FLOAT:
            case TypeInterface::TYPE_INT:
                $value = \call_user_func(Convert::class . "::convert" . \ucfirst($type), $value);
                break;
            case TypeInterface::TYPE_ARRAY:
            case TypeInterface::TYPE_CALLABLE:
            default:
                break;
        }

        return $value;
    }
}
