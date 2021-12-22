<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ExperimentInterface extends ResourceInterface, CodeAwareInterface
{
    public function __toString(): string;

    public function getId(): ?int;

    public function getGoogleExperimentId(): ?string;

    public function setGoogleExperimentId(?string $googleExperimentId): void;

    public function hasWinner(): bool;

    public function getWinner(): ?VariantInterface;

    public function setWinner(?VariantInterface $winner): void;

    /**
     * @psalm-return Collection<array-key, VariantInterface>
     *
     * @return VariantInterface[]|Collection
     */
    public function getVariants(): Collection;

    public function addVariant(VariantInterface $variant): void;

    public function removeVariant(VariantInterface $variant): void;

    public function hasVariant(VariantInterface $variant): bool;

    public function getCreatedAt(): \DateTimeInterface;

    public function getEndedAt(): ?\DateTimeInterface;

    public function setEndedAt(?\DateTimeInterface $endedAt): void;
}
