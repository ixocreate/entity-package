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
namespace KiwiSuiteTest\Entity\Type;

use KiwiSuite\Entity\Exception\InvalidTypeException;
use KiwiSuite\Entity\Exception\ServiceNotCreatedException;
use KiwiSuite\Entity\Type\Type;
use KiwiSuite\Entity\Type\TypeInterface;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use KiwiSuite\ServiceManager\SubManager\SubManager;
use KiwiSuiteMisc\Entity\MockType;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    /**
     * @var SubManager
     */
    private $subManager;

    public function setUp()
    {
        $this->subManager = new SubManager(
            new ServiceManager(new ServiceManagerConfig([]), new ServiceManagerSetup()),
            new ServiceManagerConfig([
                'factories' => [
                    MockType::class => AutowireFactory::class,
                ],
            ]),
            TypeInterface::class
        );
    }

    public function testPhpTypeCreate()
    {
        $integer = 1 ;
        $string = "string";
        $array = ["array"];
        $bool = true;
        $float = 1.1;
        $callable = function () {
        };

        $this->assertSame($integer, Type::create($integer, TypeInterface::TYPE_INT));
        $this->assertSame($string, Type::create($string, TypeInterface::TYPE_STRING));
        $this->assertSame($array, Type::create($array, TypeInterface::TYPE_ARRAY));
        $this->assertSame($bool, Type::create($bool, TypeInterface::TYPE_BOOL));
        $this->assertSame($float, Type::create($float, TypeInterface::TYPE_FLOAT));
        $this->assertSame($callable, Type::create($callable, TypeInterface::TYPE_CALLABLE));

        $this->expectException(InvalidTypeException::class);
        Type::create($integer, TypeInterface::TYPE_ARRAY);
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithSubManagerCreate()
    {
        Type::initialize($this->subManager);

        $integer = 1 ;
        $this->assertSame($integer, Type::create($integer, TypeInterface::TYPE_INT));

        $email = Type::create("noreply@kiwi-suite.com", MockType::class);
        $this->assertInstanceOf(MockType::class, $email);
        $this->assertSame("noreply@kiwi-suite.com", $email->getValue());


        $email1 = new MockType("noreply@kiwi-suite.com");
        $email1Check = Type::create($email1, MockType::class);
        $this->assertSame($email1, $email1Check);
    }

    public function testSubManagerNotSet()
    {
        $this->expectException(ServiceNotCreatedException::class);
        Type::create("noreply@kiwi-suite.com", MockType::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInvalidService()
    {
        Type::initialize($this->subManager);

        $this->expectException(ServiceNotCreatedException::class);
        Type::create("noreply@kiwi-suite.com", \DateTime::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAlreadyInitialized()
    {
        $this->expectException(ServiceNotCreatedException::class);
        Type::initialize($this->subManager);
        Type::initialize($this->subManager);
    }
}
