<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\CookieManager;

final class Experiment
{
    /**
     * This is the experiment id
     */
    public int $experiment;

    /**
     * This is the variant id
     */
    public int $variant;

    public function __construct(int $experiment, int $variant)
    {
        $this->experiment = $experiment;
        $this->variant = $variant;
    }
}
