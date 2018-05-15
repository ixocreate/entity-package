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

namespace KiwiSuite\Entity\Entity;

final class Definition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $nullAble;

    /**
     * @var bool
     */
    private $public;

    /**
     * @var bool
     */
    private $hasDefault;

    /**
     * @var mixed
     */
    private $default;

    /**
     * @var array
     */
    private $options;

    /**
     * Definition constructor.
     * @param string $name
     * @param string $type
     * @param bool $nullAble
     * @param bool $public
     * @param bool $hasDefault
     * @param null $default
     * @param array $options
     */
    public function __construct(
        string $name,
        string $type,
        bool $nullAble = true,
        bool $public = true,
        bool $hasDefault = false,
        $default = null,
        array $options = []
    ) {
        //TODO check name
        $this->name = $name;
        $this->type = $type;
        $this->nullAble = $nullAble;
        $this->public = $public;
        $this->hasDefault = $hasDefault;
        $this->default = $default;
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return bool
     */
    public function isNullAble(): bool
    {
        return $this->nullAble;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasDefault(): bool
    {
        return $this->hasDefault;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        if (is_callable($this->default)) {
            return ($this->default)();
        }
        return $this->default;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
