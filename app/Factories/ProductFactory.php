<?php

namespace App\Factories;

use App\models\Dvd;
use App\models\Product;
use App\models\Furniture;
use App\models\Book;
use Exception;


class ProductFactory {
    public static function createProduct($sku, $name, $price, $type, $value){
        $className = "App\models\\".ucfirst($type);
        if (class_exists($className)) {
            return new $className($sku, $name, $price, $type, $value);
        } else {
            throw new Exception("Invalid product type");
        }
    }
}
