<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Repository\BeerRepository;
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
    public function beer(Beer $beer): Response
    {
        return $this->render('shop/beer.html.twig', [
        ]);
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(): Response
    {
        return $this->render('shop/cart.html.twig', [
        ]);
    }
}
