<?php

namespace classes\Discounts;

use classes\Discounts\Cart as Cart;
use classes\Discounts\Products as Products;
use classes\Discounts\Customers as Customers;
use classes\Discounts\DiscountType as DiscountType;
use classes\Discounts\DiscountTypeMultiItem as DiscountTypeMultiItem;
use classes\Discounts\DiscountTypeBuyXGetYFree as DiscountTypeBuyXGetYFree;
use classes\Discounts\DiscountTypeCustomerTotal as DiscountTypeCustomerTotal;


interface DiscountInterface 
{
    public function processDiscount(Array $cartData);
    public function loadDiscounts();
}

class DiscountProcess implements DiscountInterface
{
    private $discounts;
    private $products;
    private $customers;
    private $customer;


    public function __construct(){
        $this->discounts = $this->loadDiscounts();
        $this->products = Products::loadProducts();
        $this->customers = Customers::loadCustomers();
    }

    public function processDiscount(Array $cartData){

        $newOrder = new Cart();
        $newOrder->loadCart($cartData);

        // Check if customer ID exists in database.
        if(array_search($cartData['customer-id'], array_column($this->customers, 'id'))===false)return array_merge($cartData,array("discount-error"=>"Customer ID could not be located."));
        else $this->customer = $this->customers[array_search($cartData['customer-id'], array_column($this->customers, 'id'))];
        
        // Check if the order has any items
        if(count($cartData['items'])<1)return array_merge($cartData,array("discount-error"=>"No valid discounts exist."));

        // Check if any valid discounts are available
        if(count($this->discounts)<1)return array_merge($cartData,array("discount-error"=>"No items were found in the order to apply a discount to."));

        // Define Discount Variables for Response
        $newOrder->cartData['discount-amount'] = 0;
        $newOrder->cartData['discounts-applied'] = array();

        // Clean Shopping Cart - Security and General Tidyness
        $newOrder->recalculateCart($this->products);
        
        foreach ($this->discounts as $discount){

            // Check if discount has expired (Presumably API will do this for us - but just incase!)
            if(strtotime($discount->expiration)<time())continue;

            // Apply the valid discount against the customers order.
            if($discount->type=='CustomerTotal')$newOrder->loadCart(discountTypeCustomerTotal::applyDiscount($discount, $newOrder->cartData, $this->products, $this->customer));
            else if($discount->type=='BuyXGetYFree')$newOrder->loadCart(discountTypeBuyXGetYFree::applyDiscount($discount, $newOrder->cartData, $this->products, $this->customer));
            else if($discount->type=='MultiItem')$newOrder->loadCart(discountTypeMultiItem::applyDiscount($discount, $newOrder->cartData, $this->products, $this->customer));
            
        }

        // Properly format Discount Amount and Total
        $newOrder->cartData['total'] = (string) number_format($newOrder->cartData['total'] , 2, ".", "");
        $newOrder->cartData['discount-amount'] = (string) number_format($newOrder->cartData['discount-amount'] , 2, ".", "");

        // Send back the order with applied discounts
        return $newOrder->cartData;

    }
    
    public function loadDiscounts(){
        return json_decode(file_get_contents(__DIR__ . '/discounts.json'));
    }

}
