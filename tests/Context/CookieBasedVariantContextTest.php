<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\Context;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusGoogleOptimizePlugin\Context\CookieBasedVariantContext;
use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManagerInterface;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiment as ExperimentValueObject;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiments;
use Setono\SyliusGoogleOptimizePlugin\Model\Experiment;
use Setono\SyliusGoogleOptimizePlugin\Model\Variant;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Setono\SyliusGoogleOptimizePlugin\Context\CookieBasedVariantContext
 */
final class CookieBasedVariantContextTest extends TestCase
{
    use ProphecyTrait;

    private const COOKIE_NAME = 'ssgo_experiments';

    /**
     * @test
     */
    public function it_returns_correct_variant_based_on_cookie_value(): void
    {
        $variant = new class() extends Variant {
            public function getId(): ?int
            {
                return 2;
            }

            public function getCode(): ?string
            {
                return 'variant_one';
            }
        };

        $experiment = new Experiment();
        $experiment->setCode('experiment');
        $experiment->addVariant($variant);

        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);
        $experimentProvider->hasExperiment(1)->willReturn(true);
        $experimentProvider->getExperiment(1)->willReturn($experiment);

        $cookieManager = $this->prophesize(CookieManagerInterface::class);
        $cookieManager->read(Argument::type(Request::class))->willReturn(new Experiments([new ExperimentValueObject(1, 2)]));

        $context = $this->getCookieBasedVariantContext(null, null, $experimentProvider->reveal(), $cookieManager->reveal());

        self::assertSame('variant_one', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_no_main_request_is_present(): void
    {
        $context = $this->getCookieBasedVariantContext(null, new RequestStack());

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_no_cookies_are_set(): void
    {
        $cookieManager = $this->prophesize(CookieManagerInterface::class);
        $cookieManager->read(Argument::type(Request::class))->willReturn(new Experiments());

        $context = $this->getCookieBasedVariantContext(null, null, null, $cookieManager->reveal());

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    private function getCookieBasedVariantContext(
        VariantContextInterface $decoratedVariantContext = null,
        RequestStack $requestStack = null,
        ExperimentProviderInterface $experimentProvider = null,
        CookieManagerInterface $cookieManager = null
    ): CookieBasedVariantContext {
        $decoratedVariantContext = $decoratedVariantContext ?? self::getDecoratedVariantContext();

        if (null === $requestStack) {
            $requestStack = new RequestStack();
            $requestStack->push(new Request());
        }

        if (null === $experimentProvider) {
            $experimentProvider = $this->prophesize(ExperimentProviderInterface::class)->reveal();
        }

        if (null === $cookieManager) {
            $cookieManager = $this->prophesize(CookieManagerInterface::class)->reveal();
        }

        return new CookieBasedVariantContext(
            $decoratedVariantContext,
            $requestStack,
            $experimentProvider,
            $cookieManager
        );
    }

    private static function getDecoratedVariantContext(): VariantContextInterface
    {
        return new class() implements VariantContextInterface {
            public function getVariant(string $experiment): VariantInterface
            {
                $variant = new Variant();
                $variant->setPosition(0);
                $variant->setCode('decorated');

                return $variant;
            }
        };
    }
}
