<?php

class Discounts 
{
    private $discounts;
    private $products;
    private $customers;
    private $originalOrder = array();
    private $newOrder = array();
    private $appliedDiscounts = array();


    public function __construct(){
        $this->loadDiscounts();
        $this->loadProducts();
        $this->loadCustomers();
    }

    public function processCart(Array $cartData){
        $this->originalOrder = $cartData;
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

}