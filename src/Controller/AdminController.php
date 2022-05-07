<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/add', name: '_user_add')]
    public function registerUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $isFromValid = $form->isValid();
        if (!$form->isSubmitted() || !$isFromValid) {
            return $this->render('admin/user_register.html.twig', [
                'registrationForm' => $form->createView(),
                'is_form_valid' => $isFromValid
            ]);
        }

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }
}
