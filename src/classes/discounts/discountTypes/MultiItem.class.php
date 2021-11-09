<?php

namespace classes\Discounts;

class DiscountTypeMultiItem implements DiscountType
{    
    public function applyDiscount(object $discount, array $order, array $products, object $customer){
        
        $totalValidItemsQty = 0;
        $cheapestProduct = false;
        $eligibleItemsTotal = 0;
        $eligibleItems = array();
        // Let's loop through the customers cart to check for valid items.
        foreach($order['items'] as $item){
            
            // Can we find the product in the database?
            if(array_search($item['product-id'], array_column($products, 'id'))===false)continue;
            else $activeProduct = $products[array_search($item['product-id'], array_column($products, 'id'))];
                
            // Is the Discount Category ID Valid for this item?
            if($discount->CategoryID != $activeProduct->category)continue;

            // Is the Discount Product ID Valid for this item? (-1 = Any Product in Category)
            if($discount->ProductID !=-1 && $discount->ProductID != $activeProduct->id)continue;

            // All basic verification completed - lets add the item quantities!
            $totalValidItemsQty += $item['quantity'];

            // Lets check/update the cheapest product
            if($cheapestProduct==false)$cheapestProduct = $cheapestProduct=$activeProduct;
            else if($cheapestProduct->price>$item['unit-price'])$cheapestProduct=$activeProduct;
            
            // Lets add data to eligible items 
            $eligibleItemsTotal += $item['total'];
            $eligibleItems[] = $activeProduct->id." - ".$activeProduct->description;
        }

        // Does the order contain a sufficient number of valid items?
        if($totalValidItemsQty<$discount->criteriaQty)return $order;

        // If we've got to this stage the discount is valid! Let's apply it.
        if($discount->discountType=="%"){
            
            if($discount->discountApply=='CheapestItem'){
                // Only apply the percentage discount a single cheap item?
                $order['discount-amount'] += round($cheapestProduct->price * ($discount->discountValue/100),2);
                $order['total'] -= round($cheapestProduct->price * ($discount->discountValue/100),2);
                $order['discounts-applied'][] = $discount->name . " (Applied against ".$cheapestProduct->id." - ".$cheapestProduct->description.")";

            }else if($discount->discountApply=='AllEligibleItems'){
                // Apply percentage discount to all eligible items
                $order['discount-amount'] += round($eligibleItemsTotal * ($discount->discountValue/100),2);
                $order['total'] -= round($eligibleItemsTotal * ($discount->discountValue/100),2);
                $order['discounts-applied'][] = $discount->name . " (Applied against ".implode(", ",$eligibleItems).")";
            }
        }else if($discount->discountType=="€"){
            // Apply a flat discount amount to whole order.
            $order['discount-amount'] += $discount->discountValue;
            $order['total'] -= $discount->discountValue;
            $order['discounts-applied'][] = $discount->name . " (Applied against ".$cheapestProduct->id." - ".$cheapestProduct->description.")";
        }

        return $order;
    }

}