<?php

class DiscountTypeBuyXGetYFree
{    
    public function applyDiscount(object $discount, array $order, array $products, object $customer){
        
        // Let's loop through the customers cart to check for valid items.
        foreach($order['items'] as $item){
            
            // Can we find the product in the database?
            if(array_search($item['product-id'], array_column($products, 'id'))===false)continue;
            else $activeProduct = $products[array_search($item['product-id'], array_column($products, 'id'))];
                
            // Is the Discount Category ID Valid for this item?
            if($discount->CategoryID != $activeProduct->category)continue;

            // Is the Discount Product ID Valid for this item? (-1 = Any Product in Category)
            if($discount->ProductID !=-1 && $discount->ProductID != $activeProduct->id)continue;

            // Has the quantity minimum been met?
            if($discount->criteriaQty>$item['quantity'])continue;

            // If we've got to this stage the discount is valid! Let's apply it.

            // Do we allow multiples?
            if($discount->multiples==true){
                $totalFreeItems = (floor($item['quantity']/$discount->criteriaQty)*$discount->totalFree);
            }else $totalFreeItems = $discount->totalFree;

            $order['items'][] = array(
                "product-id"  =>    $item['product-id'],
                "quantity"    =>    "$totalFreeItems",
                "unit-price"  =>    "0.00",
                "total"       =>    "0.00",
            );
            $order['discountAmount'] = $totalFreeItems*$activeProduct->price;
            $order['discountsApplied'][] = $discount->name." (Added $totalFreeItems Free of ".$activeProduct->description.")";

        }

        return $order;
    }

}