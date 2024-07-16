<?php
namespace App\Repositories;

//require_once '../../vendor/autoload.php';

use mysqli;
use App\Factories\ProductFactory;

class ProductRepository {
    private $conn;

    public function __construct($servername, $username, $password, $dbname, $port) {
        // Create connection
        $this->conn = new mysqli($servername, $username, $password, $dbname, $port);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getProductById($id){
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($product = $result->fetch_assoc()) {
            $product = ProductFactory::createProduct($product['sku'],$product['name'],$product['price'],$product['type'],$product['value']);
            return $product;
        }
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                /*switch ($row['type']) {
                    case 'DVD':
                        $product = new DVDDisc($row['id'], $row['name'], $row['price'], $row['sku'], "dvd", $row['value']);
                        break;
                    case 'Book':
                        $product = new Book($row['id'], $row['name'], $row['price'],$row['sku'],"book", $row['value']);
                        break;
                    case 'Furniture':
                        $product = new Furniture($row['id'], $row['name'], $row['price'],$row['sku'],"furniture", $row['value']);
                        break;
                    default:
                        continue 2;
                }
                $products[] = $product;*/
                //echo($row['type']);
                //$product = ProductFactory::createProduct($row['id'],$row['sku'],$row['name'],$row['price'],$row['type'],$row['value']);
                $product = ProductFactory::createProduct($row['sku'],$row['name'],$row['price'],$row['type'],$row['value']);
                //$products[] = ProductFactory::createProduct($row['id'],$row['sku'],$row['name'],$row['price'],$row['type'],$row['value']);
                $products[] = $product->makeJavascriptObject($row['id']);
            }
        }

        return $products;
    }

    public function deleteProducts($idList){
        //echo($idList);
        foreach($idList as $productId){
            //echo($productId);
            $product = $this->getProductById($productId);
            $product->deleteProduct($productId,$this->conn);
        }
    }

    public function saveProduct($product){
        $savedProduct = ProductFactory::createProduct($product['sku'],$product['name'],$product['price'],$product['type'],$product['value']);
        $savedProduct->saveProduct($this->conn);
    }

    public function __destruct() {
        $this->conn->close();
    }
}

//$connection = new ProductRepository("localhost","root","","scandiwebtask",3306);
//$products = $connection->getAllProducts();
//echo $products;
//$product = $connection->getProductById(3);
//$product->displayProduct();
//$connection->deleteProducts([3]);
