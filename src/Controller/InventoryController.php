<?php

namespace App\Controller;

use App\Entity\ShopItem;
use App\Form\ShopItemType;
use App\Repository\ShopItemRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


#[Route('/inventory', name: 'app_inventory.')]
class InventoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ShopItemRepository $repository): Response
    {
        return $this->render('inventory/index.html.twig', [
            'posts' => $repository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(ShopItem $item, TokenStorageInterface $token): Response
    {
        if($item->isRequireLogin() && !$token->getToken())
        {
            throw $this->createNotFoundException();
        }
        return $this->render('inventory/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em, UploaderService $uploader)
    {
        $item = new ShopItem();

        $form = $this->createForm(ShopItemType::class, $item);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $request->files->get('shop_item')['attatch'];
            if($file)
            {
                $filename = $uploader->uploadFile($file);

                $item->setImage($filename);
            }
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Dodano nowy przedmiot o id:'.$item->getId()."!");
            return $this->redirect($this->generateUrl('app_inventory.index'));
        }

        return $this->render('inventory/create.html.twig',
        [
            'form' => $form->createView()
        ]);
    }


    #[Route('/delete/{id}', name: 'delete')]
    public function delete(ShopItem $item, EntityManagerInterface $em): Response
    {
        $em->remove($item);
        $this->addFlash('success', 'Usunięto id:'.$item->getId()." pomyślnie!");
        $em->flush();

        return $this->redirect($this->generateUrl('app_inventory.index'));
    }
}
