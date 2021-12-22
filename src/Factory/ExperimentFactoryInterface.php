<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Factory;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface ExperimentFactoryInterface extends FactoryInterface
{
    public function createNew(): ExperimentInterface;
}
