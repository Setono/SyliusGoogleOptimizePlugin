<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\CookieManager;

use PHPUnit\Framework\TestCase;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiment;
use Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiments;

/**
 * @covers \Setono\SyliusGoogleOptimizePlugin\CookieManager\Experiments
 */
final class ExperimentsTest extends TestCase
{
    /**
     * @test
     */
    public function it_serializes_to_json(): void
    {
        $experiments = new Experiments([new Experiment(1, 2), new Experiment(3, 4)]);

        self::assertInstanceOf(\JsonSerializable::class, $experiments);
        self::assertSame('{"1":2,"3":4}', json_encode($experiments));
    }
}
