<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Factory;

use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface VariantFactoryInterface extends FactoryInterface
{
    public function createNew(): VariantInterface;

    public function createWithData(string $code, int $position): VariantInterface;
}
