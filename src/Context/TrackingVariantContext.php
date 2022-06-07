<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;
use Setono\SyliusGoogleOptimizePlugin\Stack\ViewedExperimentStackInterface;

final class TrackingVariantContext implements VariantContextInterface
{
    private VariantContextInterface $decoratedVariantContext;

    private ExperimentProviderInterface $experimentProvider;

    private ViewedExperimentStackInterface $viewedExperimentStack;

    public function __construct(
        VariantContextInterface $decoratedVariantContext,
        ExperimentProviderInterface $experimentProvider,
        ViewedExperimentStackInterface $viewedExperimentStack
    ) {
        $this->decoratedVariantContext = $decoratedVariantContext;
        $this->experimentProvider = $experimentProvider;
        $this->viewedExperimentStack = $viewedExperimentStack;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        $experimentObject = $this->experimentProvider->getExperiment($experiment);
        $variant = $this->decoratedVariantContext->getVariant($experiment);
        $this->viewedExperimentStack->add($experimentObject, $variant);

        return $variant;
    }
}
