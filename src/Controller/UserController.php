<?php

namespace App\Controller;

use App\Entity\Order;
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
        return $this->render('user/index.html.twig', ['user' => $user, 'unpaid' => null, 'unrecieved'=>null]);
    }

    #[Route(path: '/user/history', name: 'user_history_current')]
    public function userSelfHistory(TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        return $this->render('user/orders.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/addresses', name: 'user_addresses_current')]
    public function userSelfAddress(TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        return $this->render('user/addresses.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/history/{index}', name: 'user_history_id_current')]
    public function userSelfHistoryItem($index, TokenStorageInterface $tokenStorage): Response
    {
        $order = $tokenStorage->getToken()->getUser()->getOrders()[$index-1];
        return $this->render('order/order.html.twig', ['order' => $order]);
    }

    #[Route(path: '/user/order/{id}', name: 'users_history_order')]
    public function userHistoryItem(Order $order, TokenStorageInterface $tokenStorage): Response
    {
        return $this->render('order/order.html.twig', ['order' => $order]);
    }


    #[Route(path: '/user/list', name: 'user_admin_list', requirements: ['id' => '\d*'])]
    public function userList(UserRepository $ur): Response
    {
        return $this->render('user/crud/list.html.twig', ['users' => $ur->findAll([''])]);
    }

    #[Route(path: '/user/{id}/remove', name: 'user_admin_remove', requirements: ['id' => '\d*'])]
    public function userView(User $user, EntityManagerInterface $em): Response
    {
        $user->setUsername("removed-".$user->getId());
        $user->setPassword('');

        $em->flush();

        return $this->render('user/crud/list.html.twig', ['users' => $em->getRepository(User::class)->findAll()]);
    }

    #[Route(path: '/user/{id}/roles', name: 'user_admin_role', requirements: ['id' => '\d*'])]
    public function userRole(User $user): Response
    {
        return $this->render('user/crud/role.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/{id}/roles/remove/{roleid}', name: 'user_admin_role_delete', requirements: ['id' => '\d*'])]
    public function userRoleDelete(User $user, $roleid, EntityManagerInterface $em): Response
    {
        $uroles = $user->getRoles();
        unset($uroles[$roleid]);
        $uroles = array_values($uroles);
        $user->setRoles($uroles);
        dump($user);
        $em->flush();
        return $this->redirect($this->generateUrl('user_admin_role', ['id' => $user->getId()]));
    }

    #[Route(path: '/user/{id}/roles/grant/{role}', name: 'user_admin_role_grant', requirements: ['id' => '\d*'])]
    public function userRoleGrant(User $user, $role, EntityManagerInterface $em): Response
    {
        $uroles = $user->getRoles();
        array_push($uroles, $role);
        $user->setRoles($uroles);
        $em->flush();
        $this->addFlash('success', 'Użytkownik '.$user->getUsername().'('.$user->getId().') posiada teraz rolę: '.$role."!");
        return $this->redirect($this->generateUrl('user_admin_role', ['id' => $user->getId()]));
    }

    #[Route(path: '/user/{id}/history', name: 'user_history', requirements: ['id' => '\d*'])]
    public function userHistory(User $user, UserRepository $ur): Response
    {
        return $this->render('user/orders2.html.twig', ['user' => $user]);
    }

    #[Route(path: '/user/{id}/address', name: 'user_address', requirements: ['id' => '\d*'])]
    public function userAddress(User $user, UserRepository $ur): Response
    {
        return $this->render('user/addresses.html.twig', ['user' => $user]);
    }



}
