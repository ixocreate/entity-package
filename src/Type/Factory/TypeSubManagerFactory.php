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
namespace KiwiSuite\Entity\Type\Factory;

use KiwiSuite\Entity\Type\TypeInterface;
use KiwiSuite\Entity\Type\TypeServiceManagerConfig;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;

class TypeSubManagerFactory implements SubManagerFactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        return new TypeSubManager(
            $container,
            $container->get(TypeServiceManagerConfig::class),
            TypeInterface::class
        );
    }
}
