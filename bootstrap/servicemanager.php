<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity;

use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Type\TypeSubManager;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(TypeSubManager::class);
