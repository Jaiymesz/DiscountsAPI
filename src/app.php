<?php
// Disable error reporting
//error_reporting(0);

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Slim Routes
require __DIR__ . '/routes.php';
