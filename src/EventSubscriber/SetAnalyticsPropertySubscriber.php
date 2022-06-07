<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\EventSubscriber;

use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilderStackInterface;
use Setono\SyliusGoogleOptimizePlugin\Stack\ViewedExperimentStackInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SetAnalyticsPropertySubscriber implements EventSubscriberInterface
{
    private HitBuilderStackInterface $hitBuilderStack;

    private ViewedExperimentStackInterface $viewedExperimentStack;

    public function __construct(
        HitBuilderStackInterface $hitBuilderStack,
        ViewedExperimentStackInterface $viewedExperimentStack
    ) {
        $this->hitBuilderStack = $hitBuilderStack;
        $this->viewedExperimentStack = $viewedExperimentStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['set', -900], // this happens just before the hit builders are persisted
        ];
    }

    public function set(ResponseEvent $event): void
    {
        if (!$event->isMasterRequest() || $this->viewedExperimentStack->isEmpty()) {
            return;
        }

        foreach ($this->hitBuilderStack->all() as $hitBuilder) {
            foreach ($this->viewedExperimentStack->all() as [$experiment, $variant]) {
                $hitBuilder->addExperiment((string) $experiment->getGoogleExperimentId(), (int) $variant->getPosition());
            }
        }
    }
}
