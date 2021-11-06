<?php
// Disable error reporting
error_reporting(0);

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Discount Class
require __DIR__ . '/classes/Discounts.class.php';

// Slim Routes
require __DIR__ . '/routes.php';
