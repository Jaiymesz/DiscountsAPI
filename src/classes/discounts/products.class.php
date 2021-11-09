<?php

namespace classes\Discounts;

interface ProductsInterface {
    public function loadProducts();
}

class Products implements ProductsInterface {

    public function loadProducts(){
        return json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'));
    }

} 