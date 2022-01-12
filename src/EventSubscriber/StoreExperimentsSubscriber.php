<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\EventSubscriber;

use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class StoreExperimentsSubscriber implements EventSubscriberInterface
{
    private ExperimentProviderInterface $experimentProvider;

    private VariantContextInterface $variantContext;

    private string $cookieName;

    public function __construct(
        ExperimentProviderInterface $experimentProvider,
        VariantContextInterface $variantContext,
        string $cookieName
    ) {
        $this->experimentProvider = $experimentProvider;
        $this->variantContext = $variantContext;
        $this->cookieName = $cookieName;
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

        $experiments = [];
        foreach ($this->experimentProvider->getAll() as $experiment) {
            // we save the experiment => variant array as an <int, int> array because it has the smallest footprint in terms of size
            $experiments[(int) $experiment->getId()] = (int) $this->variantContext->getVariant((string) $experiment->getCode())->getId();
        }

        try {
            $cookieValue = json_encode($experiments, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->setCookie(
            Cookie::create(
                $this->cookieName,
                $cookieValue,
                (new \DateTimeImmutable())->add(new \DateInterval('P1Y'))
            )
        );
    }
}
