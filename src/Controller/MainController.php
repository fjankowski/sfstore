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

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $qb = $em->createQueryBuilder();
        $user = $tokenStorage->getToken();

        $qb
            ->select('p')
            ->from('App\Entity\ShopItem','p')
            ->andWhere('p.is_hidden = :isHidden')
            ->setParameter('isHidden', 0)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults('8');

        if(!$user)
        {
            $qb->andWhere('p.require_login = :isLog')
                ->setParameter('isLog', 0);
        }

        $products=$qb->getQuery()->getResult();

        return $this->render('home/index.html.twig',
        [
            'items' => $products
        ]);
    }

    #[Route('/search/{term}', name: 'search')]
    public function search(Request $request, EntityManagerInterface $em, $term)
    {
        $products=$em->getRepository(ShopItem::class)->createQueryBuilder('e')
        ->where('e.title LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $term . '%')
        ->getQuery()
        ->getResult();

        return $this->render('home/search.html.twig',
            [
                'items' => $products
            ]);
    }

    #[Route('/update-cart', name: 'update_cart', methods: ['POST'])]
    public function updateCart(Request $request, SessionInterface $session, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $max = $em->createQuery('SELECT MAX(p.id) FROM App\Entity\ShopItem p')->getSingleScalarResult();

        for ($i = 0; $i < sizeof($data); $i++)
        {
            if($data[$i]['quantity'] < 1 || ($data[$i]['id'] < 0 && $data[$i]['id'] > $max))
            {
                unset($data[$i]);
            }
        }

        $session->set('cart', $data);

        return new JsonResponse(['success' => true, 'cart' => $data]);
    }


}
