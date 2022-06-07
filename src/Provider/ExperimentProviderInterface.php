<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Provider;

use Setono\SyliusGoogleOptimizePlugin\Exception\NonExistingExperimentException;
use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;

interface ExperimentProviderInterface
{
    /**
     * @param int|string $experiment Either the id, code or the Google experiment id
     */
    public function hasExperiment($experiment): bool;

    /**
     * @param int|string $experiment Either the id, code or the Google experiment id
     *
     * @throws NonExistingExperimentException if the experiment does not exist
     */
    public function getExperiment($experiment): ExperimentInterface;
}
