<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Twig;

use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
use Setono\SyliusGoogleOptimizePlugin\Exception\NonExistingExperimentException;
use Twig\Extension\RuntimeExtensionInterface;

final class Runtime implements RuntimeExtensionInterface
{
    private VariantContextInterface $variantContext;

    public function __construct(VariantContextInterface $variantContext)
    {
        $this->variantContext = $variantContext;
    }

    /**
     * Returns true if
     * - the user is assigned to the given variant on the given experiment
     * - the experiment ended with the given variant as a winner
     *
     * @param string $experiment Either the experiment code or the Google experiment id
     * @param string|int|mixed $variantIdentifier Either the variant code or the position/index
     */
    public function variant(string $experiment, $variantIdentifier): bool
    {
        if (!is_string($variantIdentifier) && !is_int($variantIdentifier)) {
            throw new \InvalidArgumentException('The variant identifier must either be a string or an integer');
        }

        try {
            $variant = $this->variantContext->getVariant($experiment);
        } catch (NonExistingExperimentException $e) {
            return false;
        }

        return $variant->getPosition() === $variantIdentifier || $variant->getCode() === $variantIdentifier;
    }
}
