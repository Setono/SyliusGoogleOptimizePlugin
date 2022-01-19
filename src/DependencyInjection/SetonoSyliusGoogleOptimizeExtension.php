<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\DependencyInjection;

use Setono\GoogleAnalyticsServerSideTrackingBundle\SetonoGoogleAnalyticsServerSideTrackingBundle;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusGoogleOptimizeExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{cookie_name: string, driver: string, resources: array<string, mixed>} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_google_optimize.cookie_name', $config['cookie_name']);

        $this->registerResources('setono_sylius_google_optimize', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');

        if (class_exists(SetonoGoogleAnalyticsServerSideTrackingBundle::class)) {
            $loader->load('services/conditional/analytics.xml');
        }
    }
}
