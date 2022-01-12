<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusGoogleOptimizePlugin\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusGoogleOptimizePlugin\DependencyInjection\Configuration;
use Setono\SyliusGoogleOptimizePlugin\Doctrine\ORM\ExperimentRepository;
use Setono\SyliusGoogleOptimizePlugin\Form\Type\ExperimentType;
use Setono\SyliusGoogleOptimizePlugin\Form\Type\VariantType;
use Setono\SyliusGoogleOptimizePlugin\Model\Experiment;
use Setono\SyliusGoogleOptimizePlugin\Model\Variant;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;

/**
 * See examples of tests and configuration options here: https://github.com/SymfonyTest/SymfonyConfigTest
 */
final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function processed_value_contains_required_value(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'driver' => SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            'cookie_name' => 'ssgo_experiments',
            'resources' => [
                'experiment' => [
                    'classes' => [
                        'model' => Experiment::class,
                        'controller' => ResourceController::class,
                        'repository' => ExperimentRepository::class,
                        'form' => ExperimentType::class,
                        'factory' => Factory::class,
                    ],
                ],
                'variant' => [
                    'classes' => [
                        'model' => Variant::class,
                        'controller' => ResourceController::class,
                        'form' => VariantType::class,
                        'factory' => Factory::class,
                    ],
                ],
            ],
        ]);
    }
}
