<?php

namespace classes\Discounts;

interface DiscountType {
    public function applyDiscount(object $discount, array $order, array $products, object $customer);
}