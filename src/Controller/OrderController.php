<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProductEntry;
use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Entity\PaymentStatus;
use App\Entity\Shipping;
use App\Entity\ShippingAddress;
use App\Entity\ShippingMethod;
use App\Entity\ShippingStatus;
use App\Entity\ShopItem;
use App\Entity\User;
use App\Form\Order\PaymentForm;
use App\Form\OrderAddressForm;
use App\Form\PaymentType;
use App\Form\ShippingAddressType;
use App\Form\ShopItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/checkout')]
class OrderController extends AbstractController
{
    #[Route('', name: 'checkout')]
    public function checkout(SessionInterface $session, EntityManagerInterface $em)
    {
        $cart = $session->get('cart', []);

        $total = 0.0;
        $items = [];
        if($cart)
        {
            foreach($cart as $item)
            {
                $quantity = $item['quantity'];

                if($item['id'] < 1 || $quantity < 1)
                {
                    continue;
                }

                $prod = $em->getRepository(ShopItem::class)->findBy(['id' => $item['id']]);

                $total+=($prod[0]->getPrice()*$quantity);

                $items[] = [
                    'product' => $prod[0],
                    'count' => $quantity
                ];
            }
        }

        return $this->render('order/cartView.html.twig',
            [
                'items' => $items,
                'total' => $total
            ]);
    }

    #[Route('/shipping', name: 'checkout_shipping')]
    public function shipping(Request $request, EntityManagerInterface $em, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        if(!$session->get('cart'))
        {
            $this->addFlash('failed', 'Nieprawidłowy koszyk!');
            return $this->redirectToRoute('checkout');
        }
        if($session->get('order_data'))
        {
            $this->addFlash('failed', 'Najpierw opłać zamówienie!');
            return $this->redirectToRoute('checkout_payment');
        }

        $ship = $em->getRepository(ShippingMethod::class)->findAll();
        $user = $tokenStorage->getToken()->getUser();
        $form = $this->createForm(OrderAddressForm::class, $ship);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $address = new ShippingAddress();

            $address->setName($data['name']);
            $address->setLastname($data['lastname']);
            $address->setStreet($data['street']);
            $address->setBuildingNr($data['building_nr']);
            $address->setLocaleNr($data['locale_nr']);
            $address->setPostcode((int)str_replace('-', '', $data['postcode']));
            $address->setCity($data['city']);
            $address->setPhoneNr($data['phone_nr']);

            $foundAddress = null;

            foreach ($user->getAddresses() as $add)
            {
                if(!$add->isEqual($address)) continue;

                $foundAddress = $add;
                break;
            }
            if($foundAddress == null)
            {
                $foundAddress = $address;
                if($data['remember'])$address->setUser($user);
                $em->persist($address);
            }

            $shipping = new Shipping();
            $shipping->setMethod($data[$data['shipping_choice']]);
            $shipping->setAddress($foundAddress);
            $shipping->setStatus($em->getRepository(ShippingStatus::class)->find(['id' => 1]));

            $payment = new Payment();
            $payment->setMethod($em->getRepository(PaymentMethod::class)->find(['id' => 1]));
            $payment->setStatus($em->getRepository(PaymentStatus::class)->find(['id' => 1]));
            $payment->setPaidAmount(0);

            $order = new Order();
            $order->setUser($user);
            $order->setPayment($payment);
            $order->setShipping($shipping);
            $order->setDate(new \DateTime());

            $sum = 0.0;
            foreach($session->get('cart') as $item)
            {
                $orderEntry = new OrderProductEntry();
                $itemBuy = $em->getRepository(ShopItem::class)->find(['id' => $item['id']]);
                $orderEntry->setItem($itemBuy);
                $orderEntry->setCount($item['quantity']);
                $orderEntry->setOrderRef($order);
                $sum += $itemBuy->getPrice() * $item['quantity'];
                $order->addProduct($orderEntry);
                $em->persist($orderEntry);
            }
            $session->remove('cart');
            $order->setPrice($sum);


            $em->persist($shipping);
            $em->persist($payment);
            $em->persist($order);
            $session->set('order_data', $order);
            $em->flush();
            return $this->redirectToRoute('checkout_payment');
        }

        return $this->render('order/orderShipping.html.twig',
            [
                'shipping' => $ship,
                'form' => $form->createView(),
                'known' => $user->getAddresses()
            ]);
    }

    #[Route('/shipping/address/{id}', name: 'checkout_payment_address_json')]
    public function getAddress(int $id, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();
        $address = $em->getRepository(ShippingAddress::class)->find($id);

        if($user instanceof User && $user->getAddresses()->contains($address))
        {
            $addressData = [
                'name' => $address->getName(),
                'lastname' => $address->getLastname(),
                'street' => $address->getStreet(),
                'building_nr' => $address->getBuildingNr(),
                'locale_nr' => $address->getLocaleNr(),
                'postcode' => $address->getPostcode(),
                'city' => $address->getCity(),
                'phone_nr' => $address->getPhoneNr()
            ];

        }
        else
        {
            $addressData = [
                'name' => '',
                'lastname' => '',
                'street' => '',
                'building_nr' => '',
                'locale_nr' => '',
                'postcode' => '',
                'city' => '',
                'phone_nr' => ''
            ];
        }

        return new JsonResponse(['success' => true, 'address' => $addressData]);
    }

    #[Route('/edit', name: 'checkout_id')]
    public function checkoutForId(SessionInterface $session, EntityManagerInterface $em)
    {
        $cart = $session->get('cart');
        $order = $em->getRepository(Order::class)->find(['id' => $session->get('cart_edit')->getId()]);

        $price = 0.0;

        foreach ($order->getProducts() as $existingProduct) {
            $existingProduct->setCount(-1);
        }

        foreach ($cart as $item)
        {
            $productId = $item['id'];
            dump($productId);
            $productQuantity = $item['quantity'];
            $test = false;
            foreach ($order->getProducts() as $existingProduct) {
                $otherId = $existingProduct->getItem()->getId();
                if ($productId == $otherId) {
                    $existingProduct->setCount($productQuantity);
                    $price += $existingProduct->getItem()->getPrice() * $productQuantity;
                    $test = true;
                    break;
                }
            }

            if($test) continue;
            $item = $em->getRepository(ShopItem::class)->find(['id' => $productId]);
            $newProduct = new OrderProductEntry();
            $newProduct->setItem($item);
            $newProduct->setCount($productQuantity);
            $newProduct->setOrderRef($order);
            $order->addProduct($newProduct);
            $em->persist($newProduct);
            $price += $item->getPrice() * $productQuantity;
        }

        foreach ($order->getProducts() as $existingProduct) {
            if($existingProduct->getCount() != -1) continue;
            $order->removeProduct($existingProduct);
            $em->remove($existingProduct);
        }

        $order->setPrice($price);
        $em->flush();
        $this->addFlash('success', 'Zmieniono koszyk!');
        dump($cart);
        dump($order);
        $session->remove('cart');
        $session->remove('cart_edit');

        return $this->redirectToRoute('app_shipping_list');
    }

    #[Route('/edit/cancel', name: 'checkout_id_exit')]
    public function checkoutForIdCancel(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->addFlash('warning', 'Anulowano!');
        $session->remove('cart');
        $session->remove('cart_edit');

        return $this->redirectToRoute('app_shipping_list');
    }

    #[Route('/shipping/session/update', name: 'checkout_payment_update')]
    public function updateShipping(Request $request, SessionInterface $session, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $data = json_decode($request->getContent(), true);
        $max = $em->getRepository(ShopItem::class)->findOneBy([], ['id' => 'DESC'])->getId();

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

    #[Route('/payment/{id?}', name: 'checkout_payment')]
    public function payment($id, Request $request, SessionInterface $session, EntityManagerInterface $em)
    {
        $order = null;
        $payment = null;
        if($id)
        {
            $order = $em->getRepository(Order::class)->find(['id' => $id]);
            $payment = $order->getPayment();
        }
        else
        {
            $order = $session->get('order_data');
            if(!$order)
            {
                return $this->redirectToRoute('checkout');
            }
            $payment = $em->getRepository(Payment::class)->find(['id' => $order->getPayment()->getId()]);
        }

        $form = $this->createForm(PaymentForm::class, $payment, ['custom' => 3]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $payment_status = null;
            if($payment->getStatus()->getId() == 5)
            {
                $payment_status = $em->getRepository(PaymentStatus::class)->find(['id' => 5]);
            }
            if($order->getPrice() <= $payment->getPaidAmount())
            {
                $payment_status = $em->getRepository(PaymentStatus::class)->find(['id' => 4]);
            }
            else if($payment->getPaidAmount() == 0)
            {
                $payment_status = $em->getRepository(PaymentStatus::class)->find(['id' => 2]);
            }
            else if($order->getPrice() > $payment->getPaidAmount())
            {
                $payment_status = $em->getRepository(PaymentStatus::class)->find(['id' => 3]);
            }

            $payment->setStatus($payment_status);
            $em->flush();
            $session->remove('order_data');

            return $this->render('shipping/toshipempty.html.twig', [
                'form' => $form
            ]);
        }

        return $this->render('order/orderPayment.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/payment/{id}/admin', name: 'checkout_payment_admin')]
    public function paymentAdmin(Order $order, Request $request, SessionInterface $session, EntityManagerInterface $em)
    {

    }

    #[Route('/confirm', name: 'checkout_confirm')]
    public function confirm(SessionInterface $session)
    {
        return $this->render('home/index.html.twig');
    }
}