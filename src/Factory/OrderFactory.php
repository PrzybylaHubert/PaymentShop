<?php

namespace App\Factory;

use App\Entity\Beer;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderFactory
{
    public function create(?User $user): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        if($user) {
            $order->setUser($user);
        }

        return $order;
    }

    public function createItem(Beer $beer): OrderItem
    {
        $item = new OrderItem();
        $item->setProduct($beer);
        $item->setQuantity(1);

        return $item;
    }

}