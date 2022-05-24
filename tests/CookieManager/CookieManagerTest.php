<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\CookieManager;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManager;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Setono\SyliusGoogleOptimizePlugin\CookieManager\CookieManager
 */
final class CookieManagerTest extends TestCase
{
    use ProphecyTrait;

    private const COOKIE_NAME = 'cookie_name';

    /**
     * @test
     */
    public function it_reads_empty_array_if_main_request_is_null(): void
    {
        $manager = $this->getCookieManager(new RequestStack());
        self::assertTrue($manager->read()->isEmpty());
    }

    /**
     * @test
     */
    public function it_reads_empty_array_if_cookie_value_is_not_a_string(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [], [self::COOKIE_NAME => 1]));

        $manager = $this->getCookieManager($requestStack);
        self::assertTrue($manager->read()->isEmpty());
    }

    /**
     * @test
     */
    public function it_reads_empty_array_if_cookie_value_is_invalid_json(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [], [self::COOKIE_NAME => 'invalid json']));

        $manager = $this->getCookieManager($requestStack);
        self::assertTrue($manager->read()->isEmpty());
    }

    /**
     * @test
     */
    public function it_reads_empty_array_if_decoded_cookie_value_is_not_an_array(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [], [self::COOKIE_NAME => '0']));

        $manager = $this->getCookieManager($requestStack);
        self::assertTrue($manager->read()->isEmpty());
    }

    /**
     * @test
     */
    public function it_reads_experiments(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [], [self::COOKIE_NAME => '{"1":2,"3":10}']));

        $manager = $this->getCookieManager($requestStack);

        /** @var list<Experiment> $experiments */
        $experiments = iterator_to_array($manager->read());

        self::assertCount(2, $experiments);

        $experiment1 = $experiments[0];
        self::assertSame(1, $experiment1->experiment);
        self::assertSame(2, $experiment1->variant);

        $experiment2 = $experiments[1];
        self::assertSame(3, $experiment2->experiment);
        self::assertSame(10, $experiment2->variant);
    }

    private function getCookieManager(RequestStack $requestStack = null): CookieManager
    {
        if (null === $requestStack) {
            $requestStack = new RequestStack();
            $requestStack->push(new Request());
        }

        return new CookieManager($requestStack, self::COOKIE_NAME);
    }
}
