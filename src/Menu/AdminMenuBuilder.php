<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuBuilder
{
    public function addSection(MenuBuilderEvent $event): void
    {
        $header = $this->getHeader($event->getMenu());

        $header
            ->addChild('experiments', [
                'route' => 'setono_sylius_google_optimize_admin_experiment_index',
            ])
            ->setLabel('setono_sylius_google_optimize.menu.admin.main.configuration.experiments')
            ->setLabelAttribute('icon', 'wpexplorer')
        ;
    }

    private function getHeader(ItemInterface $menu): ItemInterface
    {
        $header = $menu->getChild('configuration');

        return $header ?? $menu->addChild('google_optimize')
            ->setLabel('setono_sylius_google_optimize.menu.admin.main.configuration.header')
        ;
    }
}
