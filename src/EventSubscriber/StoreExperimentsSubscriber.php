<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\EventSubscriber;

use Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManagerInterface;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiment;
use Setono\SyliusGoogleOptimizePlugin\Stack\ViewedExperimentStackInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class StoreExperimentsSubscriber implements EventSubscriberInterface
{
    private CookieManagerInterface $cookieManager;

    private ViewedExperimentStackInterface $viewedExperimentStack;

    public function __construct(
        CookieManagerInterface $cookieManager,
        ViewedExperimentStackInterface $viewedExperimentStack
    ) {
        $this->cookieManager = $cookieManager;
        $this->viewedExperimentStack = $viewedExperimentStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'store',
        ];
    }

    public function store(ResponseEvent $event): void
    {
        if (!$event->isMasterRequest() || $this->viewedExperimentStack->isEmpty()) {
            return;
        }

        $experiments = $this->cookieManager->read();

        foreach ($this->viewedExperimentStack->all() as [$experiment, $variant]) {
            $experiments->add(new Experiment((int) $experiment->getId(), (int) $variant->getId()));
        }

        $this->cookieManager->store($event->getResponse(), $experiments);
    }
}
