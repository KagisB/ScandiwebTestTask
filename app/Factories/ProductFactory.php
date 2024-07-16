<?php

namespace App\Factories;

use App\models\Dvd;
use App\models\Product;
use App\models\Furniture;
use App\models\Book;
use Exception;


class ProductFactory {
    //public static function createProduct($row) {
    public static function createProduct($sku, $name, $price, $type, $value){
        /*switch ($row['type']) {
            case 'DVD':
                return new DVDDisc($row['id'], $row['name'], $row['price'], $row['sku'], "dvd", $row['value']);
            case 'Book':
                return new Book($row['id'], $row['name'], $row['price'], $row['sku'], "book", $row['value']);
            case 'Furniture':
                return new Furniture($row['id'], $row['name'], $row['price'], $row['sku'], "furniture", $row['value']);
            default:
                throw new Exception("Unknown product type: " . $row['type']);
        }*/
        $className = "App\models\\".ucfirst($type);
        //echo($className);
        if (class_exists($className)) {
            return new $className($sku, $name, $price, $type, $value);
        } else {
            throw new Exception("Invalid product type");
        }
    }
}
