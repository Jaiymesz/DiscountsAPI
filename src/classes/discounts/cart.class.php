<?php

namespace classes\Discounts;

interface CartInterface {
    public function loadCart(array $cartData);
    public function recalculateCart(array $products);
}

class Cart implements CartInterface {

    public $cartData = array();

    public function loadCart(array $cartData){
        $this->cartData = $cartData;
    }

    public function recalculateCart(array $products){
        $newItems = array();
        $newTotal = 0;
        $activeProduct = array();
        // Go through each item in the order to ensure data accuracy
        foreach($this->cartData['items'] as $item){
            
            // Can we find the product in the database?
            if(array_search($item['product-id'], array_column($products, 'id'))===false)continue;
            else $activeProduct = $products[array_search($item['product-id'], array_column($products, 'id'))];

            $newItems[] = array(
                "product-id"    =>  $activeProduct->id,
                "quantity"      =>  $item['quantity'],
                "unit-price"    =>  $activeProduct->price,
                "total"         =>  (string) number_format(@($activeProduct->price * $item['quantity']), 2, ".", "")
            );

            $newTotal += $activeProduct->price * $item['quantity'];

        }
    
        // Apply Recalculated Shopping Cart
        $this->cartData['items'] = $newItems;
        $this->cartData['total'] = (string) number_format($newTotal, 2, ".", "");

        return $this->cartData;
    }

}