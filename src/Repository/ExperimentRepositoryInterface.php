<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Repository;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ExperimentRepositoryInterface extends RepositoryInterface
{
    /**
     * This method will return all experiments with variants fetch joined
     *
     * @return array<array-key, ExperimentInterface>
     */
    public function findAllWithVariants(): array;

    public function findOneByCode(string $code): ?ExperimentInterface;

    public function findOneByCodeOrGoogleExperimentId(string $experiment): ?ExperimentInterface;
}
