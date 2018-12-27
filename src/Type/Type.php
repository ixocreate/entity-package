<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity\Type;

use Ixocreate\Contract\ServiceManager\SubManager\SubManagerInterface;
use Ixocreate\Entity\Exception\InvalidTypeException;
use Ixocreate\Entity\Exception\ServiceNotCreatedException;
use Ixocreate\Entity\Type\Convert\Convert;

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

    /**
     * @param SubManagerInterface|null $subManager
     */
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
     * @param array $options
     * @return mixed
     */
    public static function create($value, string $type, array $options = [])
    {
        return self::getInstance()->doCreate($value, $type, $options);
    }

    /**
     * @param string $type
     * @return \Ixocreate\Contract\Type\TypeInterface
     */
    public static function get(string $type): \Ixocreate\Contract\Type\TypeInterface
    {
        return self::getInstance()->doGet($type);
    }

    /**
     * @param $value
     * @param string $type
     * @param array $options
     * @return mixed
     */
    private function doCreate($value, string $type, array $options = [])
    {
        $value = $this->convertValue($value, $type);

        if ($this->isPhpType($type)) {
            $functionName = "\is_" . $type;
            if (!$functionName($value)) {
                throw new InvalidTypeException(\sprintf("'%s' is not a '%s'", \gettype($value), $type));
            }

            return $value;
        }

        /** @var \Ixocreate\Contract\Type\TypeInterface $typeObject */
        $typeObject = $this->doGet($type);

        if ($value instanceof $typeObject) {
            return $value;
        }

        return $typeObject->create($value, $options);
    }

    /**
     * @param string $type
     * @return \Ixocreate\Contract\Type\TypeInterface
     */
    private function doGet(string $type): \Ixocreate\Contract\Type\TypeInterface
    {
        if (!($this->subManager instanceof SubManagerInterface)) {
            throw new ServiceNotCreatedException(\sprintf("'%s' was not initialized with a SubManager", Type::class));
        }

        if (!$this->subManager->has($type)) {
            throw new ServiceNotCreatedException(\sprintf("Can't find type '%s'", $type));
        }

        /** @var \Ixocreate\Contract\Type\TypeInterface $typeObject */
        return $this->subManager->get($type);
    }

    /**
     * @param $type
     * @return bool
     */
    private function isPhpType($type): bool
    {
        return \in_array(
            $type,
            [
                \Ixocreate\Contract\Type\TypeInterface::TYPE_STRING,
                \Ixocreate\Contract\Type\TypeInterface::TYPE_ARRAY,
                \Ixocreate\Contract\Type\TypeInterface::TYPE_BOOL,
                \Ixocreate\Contract\Type\TypeInterface::TYPE_CALLABLE,
                \Ixocreate\Contract\Type\TypeInterface::TYPE_FLOAT,
                \Ixocreate\Contract\Type\TypeInterface::TYPE_INT,
            ]
        );
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
            return $value;
        }

        switch ($type) {
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_STRING:
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_BOOL:
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_FLOAT:
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_INT:
                $value = \call_user_func(Convert::class . "::convert" . \ucfirst($type), $value);
                break;
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_ARRAY:
            case \Ixocreate\Contract\Type\TypeInterface::TYPE_CALLABLE:
            default:
                break;
        }

        return $value;
    }
}
