<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class Extension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ssgo_variant', [Runtime::class, 'variant']),
        ];
    }
}
