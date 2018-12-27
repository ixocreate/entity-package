<?php
declare(strict_types=1);
namespace Ixocreate\Entity;

use Ixocreate\Entity\Type\TypeSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(TypeSubManager::class);
