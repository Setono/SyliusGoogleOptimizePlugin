<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\Context;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusGoogleOptimizePlugin\Context\CookieBasedVariantContext;
use Setono\SyliusGoogleOptimizePlugin\Context\VariantContextInterface;
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

        $request = new Request([], [], [], [
            self::COOKIE_NAME => '{"1": 2}',
        ]);

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), $requestStack, $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('variant_one', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_no_main_request_is_present(): void
    {
        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), new RequestStack(), $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_cookie_is_not_set(): void
    {
        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);
        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), $requestStack, $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_cookie_value_is_invalid(): void
    {
        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);

        $request = new Request([], [], [], [
            self::COOKIE_NAME => 'invalid json',
        ]);

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), $requestStack, $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_decoded_cookie_value_is_not_an_array(): void
    {
        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);

        $request = new Request([], [], [], [
            self::COOKIE_NAME => '0',
        ]);

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), $requestStack, $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    /**
     * @test
     */
    public function it_returns_decorated_if_decoded_cookie_value_is_not_as_expected(): void
    {
        $experimentProvider = $this->prophesize(ExperimentProviderInterface::class);

        $request = new Request([], [], [], [
            self::COOKIE_NAME => '{"string": "string"}',
        ]);

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $context = new CookieBasedVariantContext(self::getDecoratedContext(), $requestStack, $experimentProvider->reveal(), self::COOKIE_NAME);

        self::assertSame('decorated', $context->getVariant('experiment')->getCode());
    }

    private static function getDecoratedContext(): VariantContextInterface
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
