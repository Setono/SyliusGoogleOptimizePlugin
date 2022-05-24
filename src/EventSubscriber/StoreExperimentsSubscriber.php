<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\EventSubscriber;

use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManagerInterface;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiment;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiments;
use Setono\SyliusGoogleOptimizePlugin\Repository\ExperimentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class StoreExperimentsSubscriber implements EventSubscriberInterface
{
    private CookieManagerInterface $cookieManager;

    private ExperimentRepositoryInterface $experimentRepository;

    private VariantContextInterface $variantContext;

    public function __construct(
        CookieManagerInterface $cookieManager,
        ExperimentRepositoryInterface $experimentRepository,
        VariantContextInterface $variantContext
    ) {
        $this->cookieManager = $cookieManager;
        $this->experimentRepository = $experimentRepository;
        $this->variantContext = $variantContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'store',
        ];
    }

    public function store(ResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $entities = $this->experimentRepository->findRunning();
        if ([] === $entities) {
            return;
        }

        $experiments = new Experiments();

        foreach ($entities as $experiment) {
            $experiments->add(new Experiment((int) $experiment->getId(), (int) $this->variantContext->getVariant((string) $experiment->getCode())->getId()));
        }

        $this->cookieManager->store($event->getResponse(), $experiments);
    }
}
