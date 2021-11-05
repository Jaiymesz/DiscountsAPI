<?php

class DiscountTypeMultiItem
{    

    public function __construct(){
    
    }

    public function applyDiscount(object $discount, array $order, array $products, object $customer){
        return $order;
    }

}