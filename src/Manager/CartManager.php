<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private $cartSessionStorage;
    private $cartFactory;
    private $entityManager;

    public function __construct(
        CartSessionStorage $cartSessionStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->cartSessionStorage = $cartSessionStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
    }

    public function getCurrentCart(?User $user): Order
    {
        $cart = $this->cartSessionStorage->getCart();

        if(!$cart && $user) {
            if($cart = $user->getCart()) {
                $this->cartSessionStorage->setCart($cart);
            }
        }

        if (!$cart) {
            $cart = $this->cartFactory->create($user);
        }

        return $cart;
    }

    public function save(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
}