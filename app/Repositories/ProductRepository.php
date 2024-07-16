<?php
namespace App\Repositories;

//require_once '../../vendor/autoload.php';

use mysqli;
use App\Factories\ProductFactory;

class ProductRepository {
    private $conn;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->conn = new mysqli($servername, $username, $password, $dbname, $port);

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
                $product = ProductFactory::createProduct($row['sku'],$row['name'],$row['price'],$row['type'],$row['value']);
                $products[] = $product->makeJavascriptObject($row['id']);
            }
        }

        return $products;
    }

    public function deleteProducts($idList){
        foreach($idList as $productId){
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