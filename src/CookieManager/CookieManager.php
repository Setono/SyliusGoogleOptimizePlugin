<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\CookieManager;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class CookieManager implements CookieManagerInterface
{
    private RequestStack $requestStack;

    private string $cookieName;

    public function __construct(
        RequestStack $requestStack,
        string $cookieName
    ) {
        $this->requestStack = $requestStack;
        $this->cookieName = $cookieName;
    }

    public function read(Request $request = null): Experiments
    {
        if (null === $request) {
            $request = $this->requestStack->getMasterRequest();

            if (null === $request) {
                return new Experiments();
            }
        }

        $cookieValue = $request->cookies->get($this->cookieName);
        if (!is_string($cookieValue)) {
            return new Experiments();
        }

        try {
            $experiments = json_decode($cookieValue, true, 512, \JSON_THROW_ON_ERROR);
            Assert::isArray($experiments);
        } catch (\Throwable $e) {
            return new Experiments();
        }

        $values = new Experiments();

        /**
         * @var mixed $experimentId
         * @var mixed $variantId
         */
        foreach ($experiments as $experimentId => $variantId) {
            if (!is_numeric($experimentId) || !is_numeric($variantId)) {
                // we are dealing with a messed up representation - we cannot trust the data
                return new Experiments();
            }

            $values->add(new Experiment((int) $experimentId, (int) $variantId));
        }

        return $values;
    }

    public function store(Response $response, Experiments $experiments): void
    {
        $response->headers->setCookie(Cookie::create(
            $this->cookieName,
            json_encode($experiments, \JSON_THROW_ON_ERROR),
            (new \DateTimeImmutable())->add(new \DateInterval('P1Y'))
        ));
    }
}
