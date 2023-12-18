<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('user/login/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/user', name: 'user_page')]
    public function userPanel(TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        return $this->render('user/index.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/history', name: 'user_history_current')]
    public function userSelfHistory(TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        return $this->forward('App\Controller\UserController::userHistory', ['id' => $user->getId()]);
    }

    #[Route(path: '/user/addresses', name: 'user_addresses_current')]
    public function userSelfAddress(TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        return $this->forward('App\Controller\UserController::userAddress', ['id' => $user->getId()]);
    }

    //#[Route(path: '/user/list', name: 'app_logout1', requirements: ['id' => '\d*'])]
    //#[Route(path: '/user/{id}/edit', name: 'app_logout1', requirements: ['id' => '\d*'])]
    //#[Route(path: '/user/{id}/view', name: 'app_logout1', requirements: ['id' => '\d*'])]
    //#[Route(path: '/user/{id}/remove', name: 'app_logout1', requirements: ['id' => '\d*'])]

    #[Route(path: '/user/{id?}/history', name: 'user_history', requirements: ['id' => '\d*'])]
    public function userHistory($id, UserRepository $ur): Response
    {
        $user = $ur->find(['id' => $id]);
        return $this->render('user/orders.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/{id?}/address', name: 'user_address', requirements: ['id' => '\d*'])]
    public function userAddress($id, UserRepository $ur): Response
    {
        $user = $ur->find(['id' => $id]);
        return $this->render('user/addresses.html.twig', ['user' => $user]);
    }

}
