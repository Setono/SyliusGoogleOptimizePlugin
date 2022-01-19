<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\Exception\NonExistingVariantException;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final class CookieBasedVariantContext implements VariantContextInterface
{
    private VariantContextInterface $decoratedVariantContext;

    private RequestStack $requestStack;

    private ExperimentProviderInterface $experimentProvider;

    private string $cookieName;

    public function __construct(
        VariantContextInterface $decoratedVariantContext,
        RequestStack $requestStack,
        ExperimentProviderInterface $experimentProvider,
        string $cookieName
    ) {
        $this->decoratedVariantContext = $decoratedVariantContext;
        $this->requestStack = $requestStack;
        $this->experimentProvider = $experimentProvider;
        $this->cookieName = $cookieName;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return $this->decoratedVariantContext->getVariant($experiment);
        }

        $cookieValue = $request->cookies->get($this->cookieName);
        if (!is_string($cookieValue)) {
            return $this->decoratedVariantContext->getVariant($experiment);
        }

        try {
            $experiments = json_decode($cookieValue, true, 512, \JSON_THROW_ON_ERROR);
            Assert::isArray($experiments);
        } catch (\Throwable $e) {
            return $this->decoratedVariantContext->getVariant($experiment);
        }

        /**
         * @var mixed $experimentId
         * @var mixed $variantId
         */
        foreach ($experiments as $experimentId => $variantId) {
            if (!is_numeric($experimentId) || !is_numeric($variantId)) {
                // we are dealing with a messed up representation, fallback to decorated variant context
                break;
            }

            $experimentId = (int) $experimentId;
            $variantId = (int) $variantId;

            if (!$this->experimentProvider->hasExperiment($experimentId)) {
                continue;
            }

            $obj = $this->experimentProvider->getExperiment($experimentId);

            if ($obj->getCode() !== $experiment && $obj->getGoogleExperimentId() !== $experiment) {
                continue;
            }

            $winner = $obj->getWinner();
            if (null !== $winner) {
                return $winner;
            }

            if ($obj->hasEnded()) {
                return $obj->getOriginalVariant();
            }

            foreach ($obj->getVariants() as $variant) {
                if ($variant->getId() === $variantId) {
                    return $variant;
                }
            }

            throw NonExistingVariantException::fromVariant($experiment, $variantId);
        }

        return $this->decoratedVariantContext->getVariant($experiment);
    }
}
