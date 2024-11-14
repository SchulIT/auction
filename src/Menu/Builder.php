<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

readonly class Builder {
    public function __construct(private FactoryInterface $factory)
    {
    }

    public function mainMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

        $menu->addChild('auction.overview.label', [
            'route' => 'auctions'
        ])
            ->setExtra('icon', 'fa-solid fa-money-bill-trend-up');

        $menu->addChild('profile.label', [
            'route' => 'profile'
        ])
            ->setExtra('icon', 'fa-regular fa-address-card');

        return $menu;
    }
}