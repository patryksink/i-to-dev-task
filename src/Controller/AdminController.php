<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin')]
class AdminController extends AbstractController
{
    const ADMIN_ROUTE = 'admin';
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('', name: '')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/add', name: '_user_add')]
    public function addUser(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $user = new User();
        $form = $this->getHandledForm($user, $request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $isFormValid = !$form->isSubmitted() || $form->isValid();

            return $this->render('admin/user_form.twig', [
                'userForm' => $form->createView(),
                'is_form_valid' => $isFormValid,
                'action_title' => 'Edit',
            ]);
        }

        $planPassword = $form->get('plainPassword')->getData();

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $planPassword
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $dispatcher->dispatch(new UserCreatedEvent($user, $planPassword));


        return $this->redirectToRoute(self::ADMIN_ROUTE);
    }

    #[Route('/user/edit/{id}', name: '_user_edit')]
    public function editUser(User $user, Request $request): Response
    {
        $form = $this->getHandledForm($user, $request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $isFormValid = !$form->isSubmitted() || $form->isValid();

            return $this->render('admin/user_form.twig', [
                'userForm' => $form->createView(),
                'is_form_valid' => $isFormValid,
                'action_title' => 'Edit',
            ]);
        }

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $this->entityManager->flush();

        return $this->redirectToRoute(self::ADMIN_ROUTE);
    }

    #[Route('/user/delete/{id}', name: '_user_delete')]
    public function deleteUser(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute(self::ADMIN_ROUTE);
    }

    private function getHandledForm(User $user, Request $request): FormInterface
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        return $form;
    }
}
