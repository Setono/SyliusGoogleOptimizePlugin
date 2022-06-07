<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Stack;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;

final class ViewedExperimentStack implements ViewedExperimentStackInterface
{
    /** @psalm-var array<int, array{0: ExperimentInterface, 1: VariantInterface}> */
    private array $experiments = [];

    public function add(ExperimentInterface $experiment, VariantInterface $variant): void
    {
        $this->experiments[(int) $experiment->getId()] = [$experiment, $variant];
    }

    public function all(): array
    {
        return $this->experiments;
    }

    public function isEmpty(): bool
    {
        return [] === $this->experiments;
    }

    public function count(): int
    {
        return count($this->experiments);
    }
}
