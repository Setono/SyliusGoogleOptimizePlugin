<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManagerInterface;
use Setono\SyliusGoogleOptimizePlugin\Exception\NonExistingVariantException;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedVariantContext implements VariantContextInterface
{
    private VariantContextInterface $decoratedVariantContext;

    private RequestStack $requestStack;

    private ExperimentProviderInterface $experimentProvider;

    private CookieManagerInterface $cookieManager;

    public function __construct(
        VariantContextInterface $decoratedVariantContext,
        RequestStack $requestStack,
        ExperimentProviderInterface $experimentProvider,
        CookieManagerInterface $cookieManager
    ) {
        $this->decoratedVariantContext = $decoratedVariantContext;
        $this->requestStack = $requestStack;
        $this->experimentProvider = $experimentProvider;
        $this->cookieManager = $cookieManager;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return $this->decoratedVariantContext->getVariant($experiment);
        }

        $experiments = $this->cookieManager->read($request);

        foreach ($experiments as $experimentValueObject) {
            if (!$this->experimentProvider->hasExperiment($experimentValueObject->experiment)) {
                continue;
            }

            $obj = $this->experimentProvider->getExperiment($experimentValueObject->experiment);

            if ($obj->getId() !== $experiment && $obj->getCode() !== $experiment && $obj->getGoogleExperimentId() !== $experiment) {
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
                if ($variant->getId() === $experimentValueObject->variant) {
                    return $variant;
                }
            }

            throw NonExistingVariantException::fromVariant($experiment, $experimentValueObject->variant);
        }

        return $this->decoratedVariantContext->getVariant($experiment);
    }
}
