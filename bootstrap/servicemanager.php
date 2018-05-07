<?php
declare(strict_types=1);
namespace KiwiSuite\Entity;

use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(TypeSubManager::class);
