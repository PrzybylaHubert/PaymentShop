<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Beer;

class OrderFactory
{
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

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