<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminMenuBuilder extends AbstractMenuBuilder {

    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, TranslatorInterface $translator) {
        parent::__construct($factory, $tokenStorage, $authorizationChecker, $translator);
    }

    public function adminMenu(array $options): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $menu = $root->addChild('admin', [
            'label' => ''
        ])
            ->setExtra('icon', 'fa fa-cogs')
            ->setAttribute('title', $this->translator->trans('admin.label'))
            ->setExtra('menu', 'admin')
            ->setExtra('menu-container', '#submenu')
            ->setExtra('pull-right', true);

        if($this->authorizationChecker->isGranted('ROLE_AUCTION_ADMIN')) {
            $menu->addChild('admin.label', [
                'uri' => 'admin'
            ])
                ->setLinkAttribute('target', '_blank')
                ->setExtra('icon', 'fas fa-tools');
        }

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('cron.label', [
                'route' => 'admin_cronjobs'
            ])
                ->setExtra('icon', 'fas fa-history');

            $menu->addChild('messenger.label', [
                'route' => 'admin_messenger'
            ])
                ->setExtra('icon', 'fas fa-envelope-open-text');

            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ])
                ->setExtra('icon', 'fas fa-clipboard-list');
        }

        if(count($menu->getChildren()) === 0) {
            $root->removeChild('admin');
        }

        return $root;
    }
}