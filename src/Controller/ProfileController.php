<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController {

    #[Route('/profile', name: 'profile')]
    public function index(#[CurrentUser] User $user, Request $request, UserRepositoryInterface $userRepository, ValidatorInterface $validator): Response {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userRepository->persist($user);

            $this->addFlash('success', 'profile.success');
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'needs_completion' => $validator->validate($user)->count() > 0
        ]);
    }
}