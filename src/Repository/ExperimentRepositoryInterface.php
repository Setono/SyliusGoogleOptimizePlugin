<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Repository;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ExperimentRepositoryInterface extends RepositoryInterface
{
    /**
     * @return array<array-key, ExperimentInterface>
     */
    public function findAll(bool $fetchJoinVariants = true): array;

    public function findOneByCode(string $code): ?ExperimentInterface;

    public function findOneByCodeOrGoogleExperimentId(string $experiment): ?ExperimentInterface;
}
