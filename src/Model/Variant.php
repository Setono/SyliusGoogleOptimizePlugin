<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

class Variant implements VariantInterface
{
    protected ?int $id = null;

    protected ?string $code = null;

    protected ?int $position = null;

    protected ?ExperimentInterface $experiment = null;

    public function __toString(): string
    {
        return sprintf('%s (position: %d)', (string) $this->getCode(), (int) $this->getPosition());
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getExperiment(): ?ExperimentInterface
    {
        return $this->experiment;
    }

    public function setExperiment(?ExperimentInterface $experiment): void
    {
        $this->experiment = $experiment;
    }
}
