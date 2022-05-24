<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Experiment implements ExperimentInterface
{
    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $googleExperimentId = null;

    protected ?VariantInterface $winner = null;

    /** @var Collection<array-key, VariantInterface> */
    protected Collection $variants;

    protected \DateTimeInterface $createdAt;

    protected ?\DateTimeInterface $endedAt = null;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function hasWinner(): bool
    {
        return null !== $this->winner;
    }

    public function getWinner(): ?VariantInterface
    {
        return $this->winner;
    }

    public function setWinner(?VariantInterface $winner): void
    {
        $this->winner = $winner;
        $this->endedAt = new \DateTimeImmutable();
    }

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function getOriginalVariant(): VariantInterface
    {
        foreach ($this->getVariants() as $variant) {
            if ($variant->getCode() === VariantInterface::CODE_ORIGINAL) {
                return $variant;
            }
        }

        throw new \LogicException('No original variant on this experiment');
    }

    public function addVariant(VariantInterface $variant): void
    {
        if (!$this->hasVariant($variant)) {
            $this->variants->add($variant);
            $variant->setExperiment($this);
        }
    }

    public function removeVariant(VariantInterface $variant): void
    {
        if ($this->hasVariant($variant)) {
            $this->variants->removeElement($variant);
            $variant->setExperiment(null);
        }
    }

    public function hasVariant(VariantInterface $variant): bool
    {
        return $this->variants->contains($variant);
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function hasEnded(): bool
    {
        return null !== $this->endedAt;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeInterface $endedAt): void
    {
        $this->endedAt = $endedAt;
    }
}
