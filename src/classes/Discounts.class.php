<?php

class Discounts 
{
    private $discounts;
    private $products;
    private $customers;
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
        
        // Check if the order has any items
        if(count($this->originalOrder['items'])>0){

            // Check if any valid discounts are available
            if(count($this->discounts)>0){

                foreach ($this->discounts as $discount){

                    // Check if discount has expired (Presumably API will do this for us - but just incase!)
                    if(strtotime($discount->expiration)<time())continue;

                    $discountModule = $this->newDiscountType($discount->type);
                    if(!$discountModule)continue; // If discount module loading failed (doesn't exist?) - continue loop
                    else{
                        $this->newOrder = $discountModule->applyDiscount($discount, $this->newOrder);
                    }
                }

                // Send back the order with applied discounts
                return $this->newOrder;

            }else return array_merge($this->originalOrder,array("discountError"=>"No valid discounts exist."));

        }else return array_merge($this->originalOrder,array("discountError"=>"No items were found in the order to apply a discount to."));        

    }

    private function loadDiscounts(){
        $this->discounts = json_decode(file_get_contents(__DIR__ . '/discounts.json'));
    }

    private function loadProducts(){
        $this->products = json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'));
    }

    private function loadCustomers(){
        $this->customers = json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'));
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
}