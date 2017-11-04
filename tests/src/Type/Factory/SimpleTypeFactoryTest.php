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
namespace KiwiSuiteTest\Entity\Type\Factory;

use KiwiSuite\Entity\Exception\InvalidArgumentException;
use KiwiSuite\Entity\Type\Factory\SimpleTypeFactory;
use KiwiSuite\Entity\Type\Type\Email;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use PHPUnit\Framework\TestCase;

class SimpleTypeFactoryTest extends TestCase
{
    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig([]), new ServiceManagerSetup());
    }

    public function testSimpleTypeFactory()
    {
        $factory = new SimpleTypeFactory();

        $this->assertInstanceOf(Email::class, $factory($this->serviceManager, Email::class, ['value' => 'noreply@kiwi-suite.com']));
    }

    public function testNoOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new SimpleTypeFactory();
        $factory($this->serviceManager, Email::class);
    }

    public function testEmptyOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new SimpleTypeFactory();
        $factory($this->serviceManager, Email::class, []);
    }

    public function testNoValueInOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new SimpleTypeFactory();
        $factory($this->serviceManager, Email::class, ["test" => "test"]);
    }
}
