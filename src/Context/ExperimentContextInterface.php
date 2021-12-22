<?php
declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\ValueObject\ExperimentWithAllocatedVariant;

interface ExperimentContextInterface
{
    /**
     * @return array<array-key, ExperimentWithAllocatedVariant>
     */
    public function getExperiments(): array;

    /**
     * @param string $experiment Either the code or the Google experiment id
     */
    public function hasExperiment(string $experiment): bool;

    /**
     * @param string $experiment Either the code or the Google experiment id
     * @throws \InvalidArgumentException if the experiment does not exist
     */
    public function getExperiment(string $experiment): ExperimentWithAllocatedVariant;
}
