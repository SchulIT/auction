<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
class RedirectIfUserIsNotValidListener {

    private const string RouteName = 'profile';

    public function __construct(private readonly TokenStorageInterface $tokenStorage,
                                private readonly ValidatorInterface $validator,
                                private readonly UrlGeneratorInterface $urlGenerator) {

    }

    public function __invoke(ControllerEvent $event): void {
        if(!$event->isMainRequest()) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');

        if($route === self::RouteName) {
            return;
        }

        $user = $this->tokenStorage->getToken()?->getUser();

        if(!$user instanceof User) {
            return;
        }

        $violations = $this->validator->validate($user);

        if($violations->count() > 0) {
            $event->setController(fn() => new RedirectResponse($this->urlGenerator->generate(self::RouteName)));
        }
    }
}