<?php

class DiscountTypeCustomerTotal
{
    public function applyDiscount(object $discount, array $order, array $products, object $customer){

        // Has the customer spent above the threshold?
        if($customer->revenue < $discount->total)return $order; // If not, send order back as-is.
        else{
            // The customer meets the criteria for the discount!
            if($discount->discountType=="%"){
                $order['discountAmount'] += round($order['total'] * ($discount->discountValue/100),2);
                $order['total'] -= round($order['total'] * ($discount->discountValue/100),2);
                $order['discountsApplied'][] = $discount->name;
            }else if($discount->discountType=="€"){
                $order['discountAmount'] += $discount->discountValue;
                $order['total'] -= $discount->discountValue;
                $order['discountsApplied'][] = $discount->name;
            }
            
        }
        
        return $order;
    }

}