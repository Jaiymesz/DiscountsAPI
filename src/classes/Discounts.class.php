<?php

class Discounts 
{
    private $discounts;
    private $products;
    private $customers;
    private $customer;
    private $originalOrder = array();
    private $newOrder = array();


    public function __construct(){
        $this->loadDiscounts();
        $this->loadProducts();
        $this->loadCustomers();
    }

    public function processCart(Array $cartData){

        $this->originalOrder = $cartData;
        $this->newOrder = $cartData;

        // Check if customer ID exists in database.
        if(array_search($this->originalOrder['customer-id'], array_column($this->customers, 'id'))===false)return array_merge($this->originalOrder,array("discount-error"=>"Customer ID could not be located."));
        else $this->customer = $this->customers[array_search($this->originalOrder['customer-id'], array_column($this->customers, 'id'))];
        
        // Check if the order has any items
        if(count($this->originalOrder['items'])<1)return array_merge($this->originalOrder,array("discount-error"=>"No valid discounts exist."));

        // Check if any valid discounts are available
        if(count($this->discounts)<1)return array_merge($this->originalOrder,array("discount-error"=>"No items were found in the order to apply a discount to."));

        // Define Discount Variables for Response
        $this->newOrder['discount-amount'] = 0;
        $this->newOrder['discounts-applied'] = array();

        // Clean Shopping Cart - Security and General Tidyness
        $this->newOrder = $this->recalculateCart($this->newOrder, $this->products);
        
        foreach ($this->discounts as $discount){

            // Check if discount has expired (Presumably API will do this for us - but just incase!)
            if(strtotime($discount->expiration)<time())continue;

            // Load Discount Type Class
            $discountModule = $this->newDiscountType($discount->type);

            if(!$discountModule)continue; // If discount module loading failed (doesn't exist?) - continue loop
            else{
                // Apply the valid discount against the customers order.
                $this->newOrder = $discountModule->applyDiscount($discount, $this->newOrder, $this->products, $this->customer);
            }
        }

        // Properly format Discount Amount and Total
        $this->newOrder['total'] = (string) number_format($this->newOrder['total'] , 2, ".", "");
        $this->newOrder['discount-amount'] = (string) number_format($this->newOrder['discount-amount'] , 2, ".", "");

        // Send back the order with applied discounts
        return $this->newOrder;

    }

    private function recalculateCart(Array $cartData, array $products){

        $newItems = array();
        $newTotal = 0;
        $activeProduct = array();
        // Go through each item in the order to ensure data accuracy
        foreach($cartData['items'] as $item){
            
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
        $cartData['items'] = $newItems;
        $cartData['total'] = (string) number_format($newTotal, 2, ".", "");

        return $cartData;
    }

    private function newDiscountType(string $type){

        // Check if the Discount Type class is available
        if(file_exists(__DIR__."/discountTypes/".preg_replace("/[^a-zA-Z0-9\s]/", "",$type).".class.php")){

            // If valid - attempt to load if not already.
            include_once(__DIR__."/discountTypes/".preg_replace("/[^a-zA-Z0-9\s]/", "",$type).".class.php");

            // Create New Object of Loaded Class
            $className = "DiscountType".$type;
            return new $className;

        }else return false;        
    }
    
    private function loadDiscounts(){
        $this->discounts = json_decode(file_get_contents(__DIR__ . '/discounts.json'));
    }

    private function loadProducts(){
        $this->products = json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'));
    }

    private function loadCustomers(){
        $this->customers = json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/customers.json'));
    }

}