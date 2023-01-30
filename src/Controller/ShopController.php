<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Form\CartType;
use App\Form\AddToCartType;
use App\Manager\CartManager;
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

    #[Route('/cart', name: 'app_cart')]
    public function cart(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentCart($this->getUser());
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTimeImmutable());
            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart');
        }

        return $this->render('shop/cart.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }
}
