<?php
declare(strict_types=1);
namespace Ixocreate\Entity\Package;

use Ixocreate\Entity\Package\Type\TypeSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(TypeSubManager::class);
