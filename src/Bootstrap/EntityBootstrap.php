<?php
namespace KiwiSuite\Entity\Bootstrap;

use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\Entity\ConfiguratorItem\TypeConfiguratorItem;
use KiwiSuite\Entity\Type\Factory\TypeSubManagerFactory;
use KiwiSuite\Entity\Type\Type;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManager;

final class EntityBootstrap implements BootstrapInterface
{

    /**
     * @param ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(ConfiguratorRegistry $configuratorRegistry): void
    {
        $configuratorRegistry->getConfigurator("serviceManagerConfigurator")->addSubManager(TypeSubManager::class, TypeSubManagerFactory::class);
    }

    /**
     * @param ServiceRegistry $serviceRegistry
     */
    public function addServices(ServiceRegistry $serviceRegistry): void
    {

    }

    /**
     * @return array|null
     */
    public function getConfiguratorItems(): ?array
    {
        return [
            TypeConfiguratorItem::class
        ];
    }

    /**
     * @return array|null
     */
    public function getDefaultConfig(): ?array
    {
        return null;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function boot(ServiceManager $serviceManager): void
    {
        Type::initialize($serviceManager->get(TypeSubManager::class));
    }
}
