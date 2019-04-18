<?php
declare(strict_types=1);
namespace Ixocreate\Package\Entity;

use Ixocreate\Package\Entity\Type\TypeSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(TypeSubManager::class);
