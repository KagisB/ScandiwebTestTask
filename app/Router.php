<?php
require_once "../vendor/autoload.php";

use App\Repositories\ProductRepository;


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}