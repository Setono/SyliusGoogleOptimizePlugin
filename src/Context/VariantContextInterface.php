<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;

interface VariantContextInterface
{
    /**
     * If the experiment is running, it will return the variant assigned to the current user
     * If the experiment has ended with a winner, it will return the winner variant
     * If the experiment has ended without a winner, it will return the original variant
     *
     * @param string $experiment Either the code or the Google experiment id
     *
     * @throws \InvalidArgumentException if the experiment does not exist
     */
    public function getVariant(string $experiment): VariantInterface;
}
