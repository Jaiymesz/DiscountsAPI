<?php

class DiscountTypeCustomerTotal
{
    public function applyDiscount(object $discount, array $order, array $products, object $customer){

        // Has the customer spent above the threshold?
        if($customer->revenue > $discount->total){

            if($discount->discountType=="%"){
                // Apply percentage discount to whole order
                $order['discount-amount'] += round($order['total'] * ($discount->discountValue/100),2);
                $order['total'] -= round($order['total'] * ($discount->discountValue/100),2);
                $order['discounts-applied'][] = $discount->name;

            }else if($discount->discountType=="€"){
                // Apply a flat discount amount to whole order.
                $order['discount-amount'] += $discount->discountValue;
                $order['total'] -= $discount->discountValue;
                $order['discounts-applied'][] = $discount->name;
            }
            
        }
        
        return $order;
    }

}