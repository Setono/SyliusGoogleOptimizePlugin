<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\BotDetectionBundle\BotDetector\BotDetectorInterface;
use Setono\SyliusGoogleOptimizePlugin\Factory\VariantFactoryInterface;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;

final class BotRequestAwareVariantContext implements VariantContextInterface
{
    private VariantContextInterface $decoratedVariantContext;

    private BotDetectorInterface $botDetector;

    private VariantFactoryInterface $variantFactory;

    public function __construct(VariantContextInterface $decoratedVariantContext, BotDetectorInterface $botDetector, VariantFactoryInterface $variantFactory)
    {
        $this->decoratedVariantContext = $decoratedVariantContext;
        $this->botDetector = $botDetector;
        $this->variantFactory = $variantFactory;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        if ($this->botDetector->isBotRequest()) {
            return $this->variantFactory->createOriginal();
        }

        return $this->decoratedVariantContext->getVariant($experiment);
    }
}
