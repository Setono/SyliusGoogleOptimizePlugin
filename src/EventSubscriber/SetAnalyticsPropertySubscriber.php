<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\EventSubscriber;

use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilderStackInterface;
use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
use Setono\SyliusGoogleOptimizePlugin\Repository\ExperimentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SetAnalyticsPropertySubscriber implements EventSubscriberInterface
{
    private HitBuilderStackInterface $hitBuilderStack;

    private ExperimentRepositoryInterface $experimentRepository;

    private VariantContextInterface $variantContext;

    public function __construct(
        HitBuilderStackInterface $hitBuilderStack,
        ExperimentRepositoryInterface $experimentRepository,
        VariantContextInterface $variantContext
    ) {
        $this->hitBuilderStack = $hitBuilderStack;
        $this->experimentRepository = $experimentRepository;
        $this->variantContext = $variantContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['set', -900], // this happens just before the hit builders are persisted
        ];
    }

    public function set(ResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $experiments = $this->experimentRepository->findRunning();

        foreach ($this->hitBuilderStack->all() as $hitBuilder) {
            foreach ($experiments as $experiment) {
                $variant = $this->variantContext->getVariant((string) $experiment->getCode());

                $hitBuilder->addExperiment((string) $experiment->getGoogleExperimentId(), (int) $variant->getPosition());
            }
        }
    }
}
