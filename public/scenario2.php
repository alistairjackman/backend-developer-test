<?php
require_once __DIR__ . '../../vendor/autoload.php';

use WiQApp\GreatFoodApiController;

try {
    $greatFoodApi = new GreatFoodApiController();
    $product      = [
        "id" => 7,
        "name" => "Chips"
    ];
    $greatFoodApi->updateProductByMenuIdAndProductId(84, 7, $product);
} catch (\Exception $e) {
    die('Error occurred: ' . $e->getMessage() . ' ' . $e->getCode());
}
