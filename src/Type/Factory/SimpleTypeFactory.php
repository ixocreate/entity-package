<?php
declare(strict_types=1);
namespace KiwiSuite\Entity\Type\Factory;

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
            //TODO Exception
        }

        if (!\array_key_exists('value', $options)) {
            //TODO Exception
        }

        return new $requestedName($options['value']);
    }
}
