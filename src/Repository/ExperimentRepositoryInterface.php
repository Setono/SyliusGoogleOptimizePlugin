<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Repository;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ExperimentRepositoryInterface extends RepositoryInterface
{
    /**
     * @return list<ExperimentInterface>
     */
    public function findAll(bool $fetchJoinVariants = true): array;

    /**
     * Returns a list of running experiments
     *
     * @return list<ExperimentInterface>
     */
    public function findRunning(): array;

    public function findOneByCode(string $code): ?ExperimentInterface;

    public function findOneByCodeOrGoogleExperimentId(string $experiment): ?ExperimentInterface;
}
