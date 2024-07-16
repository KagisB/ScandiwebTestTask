<?php
require_once "../vendor/autoload.php";

use App\Repositories\ProductRepository;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*$_SERVER['REQUEST_METHOD']='POST';
$data=[
    'sku' => "testSKUU",
    'name' => "boC",
    'price' => 12.02,
    'type' => "book",
    'value' => "2.02"
];*/
//echo $_SERVER['REQUEST_METHOD'];
//echo $_POST['action'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    if(isset($_GET["action"])){
        switch($_GET["action"]){
            case 'save':
                $rawData = file_get_contents("php://input");

                $data = json_decode($rawData, true);

                if ($data) {
                    $productRepository = new ProductRepository("localhost","root","","scandiwebtask",3306);
                    $productRepository->saveProduct($data);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Invalid JSON data'
                    ]);
                }
                break;
            case 'delete':
                $rawData = file_get_contents("php://input");

                $data = json_decode($rawData);

                if($data){
                    $productRepository = new ProductRepository("localhost","root","","scandiwebtask",3306);
                    $productRepository->deleteProducts($data);
                } else{
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Invalid ID list'
                    ]);
                }
        }
    }

    /*
    $product = [
        'sku' => $_POST["sku"],
        'name' => $_POST["name"],
        'price' => $_POST["price"],
        'type' => $_POST["productType"]
    ];
    switch($_POST["productType"]){
        case "dvd":
            $product["value"] = $_POST["size"];
            break;
        case "book":
            $product["value"] = $_POST["weight"];
            break;
        case "furniture":
            $product["value"] = $_POST["height"]."x".$_POST["width"]."x".$_POST["length"];
            break;
    }
    if($product){
        $productRepository = new ProductRepository("localhost","root","","scandiwebtask",3306);
        $productRepository->saveProduct($product);
        header("location: ../views/ProductList.php");
        exit;
    }*/
} else {
    //header("location: ../views/AddProduct.php");
    //exit;
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}