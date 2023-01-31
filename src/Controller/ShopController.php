<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Form\CartType;
use App\Entity\OrderItem;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Service\PaymentService;
use App\Form\CheckoutSubmitType;
use App\Repository\BeerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop')]
    public function index(BeerRepository $beerRepository): Response
    {
        $beers = $beerRepository->findAll();
        return $this->render('shop/index.html.twig', [
            'beers' => $beers,
        ]);
    }

    #[Route('/beer/{id}', name: 'app_beer')]
    public function beer(Beer $beer, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setBeer($beer);

            $cart = $cartManager->getCurrentCart($this->getUser());
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTimeImmutable());

            $cartManager->save($cart);

            return $this->redirectToRoute('app_beer', ['id' => $beer->getId()]);
        }

        return $this->render('shop/beer.html.twig', [
            'beer' => $beer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add/{id}', name: 'app_add_beer')]
    public function add(Beer $beer, CartManager $cartManager): Response
    {
            $item = new OrderItem();
            $item->setQuantity(1);
            $item->setBeer($beer);

            $cart = $cartManager->getCurrentCart($this->getUser());
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTimeImmutable());

            $cartManager->save($cart);

            return $this->redirectToRoute('app_shop');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(CartManager $cartManager, Request $request, PaymentService $paymentService): Response
    {
        $cart = $cartManager->getCurrentCart($this->getUser());

        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        $checkoutForm = $this->createForm(CheckoutSubmitType::class);
        $checkoutForm->handleRequest($request);

        if ($checkoutForm->isSubmitted() && $checkoutForm->isValid()) {
            $priceString = sprintf("%s", $cart->getTotal());
            $test = $paymentService->getOrderedParameters($cart->getTotal());

            return $this->redirect("https://ssl.dotpay.pl/test_payment/?id=$test[id]&amount=$test[amount]&currency=$test[currency]&description=$test[description]&chk=$test[chk]&lang=$test[lang]");
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTimeImmutable());
            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart');
        }

        return $this->render('shop/cart.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
            'checkout' => $checkoutForm->createView()
        ]);
    }
}
