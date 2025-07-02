<?php
namespace App\Repository;

interface OrderRepositoryInterface
{
    public function placeOrder(array $data, array $cart);
}