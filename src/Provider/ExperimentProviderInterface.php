<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Provider;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;

interface ExperimentProviderInterface
{
    /**
     * @param int|string $experiment Either the id, code or the Google experiment id
     */
    public function hasExperiment($experiment): bool;

    /**
     * @param int|string $experiment Either the id, code or the Google experiment id
     */
    public function getExperiment($experiment): ExperimentInterface;

    /**
     * @return array<int|string, ExperimentInterface>
     */
    public function getAll(): array;
}
