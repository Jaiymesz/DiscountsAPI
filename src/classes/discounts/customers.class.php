<?php

namespace classes\Discounts;

interface CustomersInterface {
    public function loadCustomers();
}

class Customers implements CustomersInterface {

    public function loadCustomers(){
       return json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/customers.json'));
    }

} 