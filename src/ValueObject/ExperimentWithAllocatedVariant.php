<?php
declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\ValueObject;

final class ExperimentWithAllocatedVariant
{
    private string $code;
    private string $googleExperimentId;
    private Variant $variant;

    public function __construct(string $code, string $googleExperimentId, Variant $variant)
    {
        $this->code = $code;
        $this->googleExperimentId = $googleExperimentId;
        $this->variant = $variant;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getGoogleExperimentId(): string
    {
        return $this->googleExperimentId;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }
}
