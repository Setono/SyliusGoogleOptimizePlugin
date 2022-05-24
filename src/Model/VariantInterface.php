<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface VariantInterface extends ResourceInterface, CodeAwareInterface
{
    /**
     * This represents the position for original variants, which MUST be reserved for original variants
     */
    public const POSITION_ORIGINAL = 0;

    /**
     * This represents the code for original variants, which MUST be reserved for original variants
     */
    public const CODE_ORIGINAL = 'original';

    public function __toString(): string;

    public function getId(): ?int;

    /**
     * The position refers to the position/index in the Google Optimize interface. You MUST set the position to the
     * same as it is in the Google Optimize interface to have the correct results
     */
    public function getPosition(): ?int;

    public function setPosition(?int $position): void;

    public function getExperiment(): ?ExperimentInterface;

    public function setExperiment(?ExperimentInterface $experiment): void;
}
