<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Repository;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ExperimentRepositoryInterface extends RepositoryInterface
{
    public function findOneByCodeOrGoogleExperimentId(string $experiment): ?ExperimentInterface;
}
