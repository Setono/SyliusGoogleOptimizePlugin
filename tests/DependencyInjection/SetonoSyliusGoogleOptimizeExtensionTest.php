<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\SyliusGoogleOptimizePlugin\DependencyInjection\SetonoSyliusGoogleOptimizeExtension;

/**
 * See examples of tests and configuration options here: https://github.com/SymfonyTest/SymfonyDependencyInjectionTest
 */
final class SetonoSyliusGoogleOptimizeExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoSyliusGoogleOptimizeExtension(),
        ];
    }

    /**
     * @test
     */
    public function after_loading_the_correct_parameter_has_been_set(): void
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('setono_sylius_google_optimize.cookie_name', 'ssgo_exp');
    }
}
