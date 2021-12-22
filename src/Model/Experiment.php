<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

class Experiment implements ExperimentInterface
{
    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $googleExperimentId = null;

    protected ?int $variants = null;

    public function __toString(): string
    {
        return sprintf('%s (%s)', (string) $this->getCode(), (string) $this->getGoogleExperimentId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getGoogleExperimentId(): ?string
    {
        return $this->googleExperimentId;
    }

    public function setGoogleExperimentId(?string $googleExperimentId): void
    {
        $this->googleExperimentId = $googleExperimentId;
    }

    public function getVariants(): ?int
    {
        return $this->variants;
    }

    public function setVariants(?int $variants): void
    {
        $this->variants = $variants;
    }
}
