<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ExperimentInterface extends ResourceInterface, CodeAwareInterface
{
    public function __toString(): string;

    public function getId(): ?int;

    public function getGoogleExperimentId(): ?string;

    public function setGoogleExperimentId(?string $googleExperimentId): void;

    /**
     * The number of variants in this experiment
     */
    public function getVariants(): ?int;

    public function setVariants(?int $variants): void;
}
