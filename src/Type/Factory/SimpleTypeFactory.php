<?php
declare(strict_types=1);
namespace KiwiSuite\Entity\Type\Factory;

use KiwiSuite\Entity\Exception\InvalidArgumentException;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;

class SimpleTypeFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options) || !\is_array($options)) {
            throw new InvalidArgumentException(sprintf("'%s' can only be received through the build method with valid options", $requestedName));
        }

        if (!\array_key_exists('value', $options)) {
            throw  new InvalidArgumentException(sprintf("'%s' was build without a 'value' option", $requestedName));
        }

        return new $requestedName($options['value']);
    }
}
