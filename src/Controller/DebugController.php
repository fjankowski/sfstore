<?php

namespace App\Controller;

use App\Entity\ShopItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/debug')]
class DebugController extends AbstractController
{
    #[Route('/setrole/{role}', name: 'debug_role')]
    public function role($role, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {

        $user = $tokenStorage->getToken()->getUser();
        dump($user);
        $user->setRoles([$role]);
        $em->flush();

        return $this->render('debug.html.twig',
            [
                'message' => "Changed role for ".$user->getUsername()." to ".$role
            ]);
    }
}
