<?php
require_once __DIR__ . '../../vendor/autoload.php';

use WiQApp\GreatFoodApiController;

try {
    $greatFoodApi = new GreatFoodApiController();
    $products     = $greatFoodApi->getMenuProductsByMenuName("Takeaway");
    print_r($products);
} catch (\Exception $e) {
    die('Error occurred: ' . $e->getMessage() . ' ' . $e->getCode());
}

