<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ECSConfig $containerConfigurator): void {
    $containerConfigurator->import('vendor/sylius-labs/coding-standard/ecs.php');
    $containerConfigurator->parameters()->set(Option::PATHS, [
        'src', 'tests'
    ]);
    $containerConfigurator->parameters()->set(Option::SKIP, [
        'tests/Application/node_modules/**',
        'tests/Application/var/**',
    ]);
};
