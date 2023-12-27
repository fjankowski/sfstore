<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\PaymentStatus;
use App\Entity\Shipping;
use App\Entity\ShippingAddress;
use App\Entity\ShopItem;
use App\Form\PaymentType;
use App\Form\ShippingType;
use App\Form\ToShipForm;
use App\Repository\OrderProductEntryRepository;
use App\Repository\OrderRepository;
use App\Repository\ShippingRepository;
use App\Repository\ShippingStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Driver\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/shipping')]
class ShippingController extends AbstractController
{
    #[Route('/', name: 'app_shipping_index')]
    public function index(ShippingRepository $shippingRepository): Response
    {
        return $this->render('shipping/index.html.twig', [
            'shippings' => $shippingRepository->findAll(),
        ]);
    }

    #[Route('/to_ship', name: 'app_shipping_ship')]
    public function toShip(EntityManagerInterface $em, OrderRepository $orderRepository, ShippingStatusRepository $ss, SessionInterface $session): Response
    {
        $order = $orderRepository->createQueryBuilder('o')
            ->join('o.shipping', 's')
            ->join('s.status', 'ss')
            ->where('ss.id <= :num')
            ->setParameter('num', 2)
            ->getQuery()
            ->getResult();


        $shippingData = $session->get('toShip_data', []);

        if($order)
        {
            $products = $order[0]->getProducts();
            $order[0]->getShipping()->setStatus($ss->find(['id' => 2]));
            $em->flush();
            if($products)
            {
                $items = [];
                foreach ($products as $orderProduct) {
                    $items[] = [
                        'product' => $orderProduct->getItem(),
                        'count' => $orderProduct->getCount(),
                    ];
                }

                if(sizeof($shippingData) == sizeof($items))
                {
                    $test = true;
                    for($i = 0; $i < sizeof($items); $i++)
                    {
                        if($shippingData[$i]['quantity'] != $items[$i]['count'] || $shippingData[$i]['id'] != $items[$i]['product']->getId())
                        {
                            $test = false;
                            break;
                        }
                    }
                    if($test)
                    {
                        $session->remove('toShip_data');
                        $order[0]->getShipping()->setStatus($ss->find(['id' => 3]));
                        $order[0]->getShipping()->setShippedDate(new \DateTime());
                        $em->flush();
                        return $this->redirectToRoute('app_shipping_ship');
                    }
                }

                return $this->render('shipping/toship.html.twig', [
                    'items' => $items,
                    'id' => $order[0]->getId(),
                    'address' => $order[0]->getShipping()->getAddress()
                ]);
            }
        }
        return $this->render('shipping/toshipempty.html.twig');
    }

    #[Route('/to_ship/save', name: 'app_shipping_ship_save')]
    public function toShipSave(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $session->set('toShip_data', $data);
        return new JsonResponse(['success' => true]);
    }

    #[Route('/list', name: 'app_shipping_list')]
    public function list(OrderRepository $or): Response
    {
        return $this->render('shipping/list.html.twig', [
            'orders' => $or->findAll(),
        ]);
    }

    #[Route('/list/{id}/remove', name: 'app_shipping_list_page')]
    public function listId(ShippingRepository $shippingRepository): Response
    {
        return $this->render('shipping/page.html.twig', [
            'shippings' => $shippingRepository->findAll(),
        ]);
    }

    #[Route('/list/{id}/edit/shipping', name: 'app_shipping_edit_s')]
    public function editShipping(Request $request, Order $order, EntityManagerInterface $em): Response
    {
        $shipping = $order->getShipping();
        $form = $this->createForm(ShippingType::class, $shipping);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_shipping_list');
        }

        return $this->render('order/orderPayment.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/list/{id}/edit/content', name: 'app_shipping_edit_c')]
    public function editContent(Request $request, Order $order, EntityManagerInterface $em): Response
    {
        return $this->render('shipping/content.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/list/{id}/edit/content/json', name: 'app_shipping_edit_c_json', methods: 'POST')]
    public function getContent(SessionInterface $session, Order $order, EntityManagerInterface $em): Response
    {
        $output = [];
        $data = $order->getProducts();

        for ($i = 0; $i < sizeof($data); $i++)
        {
            $output[] = ['id' => $data[$i]->getItem()->getId(), 'quantity' => $data[$i]->getCount()];
        }

        $session->set('cart', $output);
        $session->set('cart_edit', $order);

        return new JsonResponse(['success' => true, 'cart' => $output]);
    }

    #[Route('/list/{id}/edit/payment', name: 'app_shipping_edit_p')]
    public function editPayment(Request $request, Order $order, EntityManagerInterface $em): Response
    {
        $payment = $order->getPayment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_shipping_list');
        }

        return $this->render('order/orderPayment.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/list/{id}/edit/payment/admin', name: 'app_shipping_edit_pad')]
    public function editPaymentAdmin(Request $request, Order $order, EntityManagerInterface $em): Response
    {
        $pay = $order->getPayment();
        $pay->setMethod($em->getRepository(PaymentMethod::class)->find(['id' => 2]));
        $pay->setStatus($em->getRepository(PaymentStatus::class)->find(['id' => 5]));
        $pay->setPaidAmount($order->getPrice());

        $em->flush();

        $referer = $request->headers->get('referer');

        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('home');
    }
}
