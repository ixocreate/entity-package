<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Entity\Type;

use Ixocreate\Package\Type\TypeInterface;
use Ixocreate\Package\Entity\Exception\InvalidTypeException;
use Ixocreate\Package\Entity\Exception\ServiceNotCreatedException;
use Ixocreate\Package\Entity\Type\Type;
use Ixocreate\ServiceManager\Factory\AutowireFactory;
use Ixocreate\ServiceManager\ServiceManager;
use Ixocreate\ServiceManager\ServiceManagerConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\ServiceManager\ServiceManagerSetup;
use Ixocreate\ServiceManager\SubManager\SubManager;
use Ixocreate\Misc\Entity\MockType;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    /**
     * @var SubManager
     */
    private $subManager;

    public function setUp()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addFactory(MockType::class, AutowireFactory::class);

        $this->subManager = new SubManager(
            new ServiceManager(new ServiceManagerConfig(new ServiceManagerConfigurator()), new ServiceManagerSetup()),
            new ServiceManagerConfig($serviceManagerConfigurator),
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

        $email = Type::create("noreply@example.com", MockType::class);
        $this->assertInstanceOf(MockType::class, $email);
        $this->assertSame("noreply@example.com", $email->getValue());


        $email1 = new MockType("noreply@example.com");
        $email1Check = Type::create($email1, MockType::class);
        $this->assertSame($email1, $email1Check);
    }

    public function testSubManagerNotSet()
    {
        $this->expectException(ServiceNotCreatedException::class);
        Type::create("noreply@example.com", MockType::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInvalidService()
    {
        Type::initialize($this->subManager);

        $this->expectException(ServiceNotCreatedException::class);
        Type::create("noreply@example.com", \DateTime::class);
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
