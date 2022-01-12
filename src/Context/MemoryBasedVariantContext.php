<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;

final class MemoryBasedVariantContext implements VariantContextInterface
{
    /** @var array<string, VariantInterface> */
    private array $experiments = [];

    private VariantContextInterface $decoratedVariantContext;

    public function __construct(VariantContextInterface $decoratedVariantContext)
    {
        $this->decoratedVariantContext = $decoratedVariantContext;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        if (!isset($this->experiments[$experiment])) {
            $this->experiments[$experiment] = $this->decoratedVariantContext->getVariant($experiment);
        }

        return $this->experiments[$experiment];
    }
}
