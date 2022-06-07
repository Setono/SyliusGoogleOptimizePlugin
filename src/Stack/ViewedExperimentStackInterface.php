<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Stack;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;

interface ViewedExperimentStackInterface extends \Countable
{
    public function add(ExperimentInterface $experiment, VariantInterface $variant): void;

    /**
     * @psalm-return array<int, array{0: ExperimentInterface, 1: VariantInterface}>
     */
    public function all(): array;

    public function isEmpty(): bool;
}
