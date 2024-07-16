<?php

namespace App\models;

class Book extends Product
{
    private $weight;

    public function __construct($sku, $name, $price, $type, $value)
    {
        parent::__construct($sku, $name, $price, $type);
        $this->setWeight($value);
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function displayProduct()
    {
        echo "Book: " . $this->getName() . ", Price: " . $this->getPrice() . ", Sku: " . $this->getSku() . ", Weight(KG): " . $this->getWeight();
    }

    public function saveProduct($conn)
    {
        if ($conn->connect_error) {
            die('Connection Error: (' . $conn->connect_errno . ') ' . $conn->connect_error);
        }

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $type = "book";
        $weight = $this->getWeight();

        $stmt1 = $conn->prepare("SELECT * from products where sku = ?");
        $stmt1->bind_param('s',$sku);
        $stmt1->execute();
        $result = $stmt1->get_result();

        if ($result->fetch_assoc()) {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, type = ?, value = ? WHERE sku = ?");
            $stmt->bind_param('sdsds', $name, $price, $type, $weight, $sku);
        } else {
            // Insert new product
            $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, value) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssdss',$sku, $name, $price, $type, $weight);
        }

        $stmt->execute();

        if (!$this->getId()) {
            $this->setId($conn->insert_id);
        }

        $stmt->close();
    }


    public function loadProduct($id,$conn)
    {

        if ($conn->connect_error) {
            die('Connection Error: (' . $conn->connect_errno . ') ' . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($product = $result->fetch_assoc()) {
            $this->setId($product['id']);
            $this->setName($product['name']);
            $this->setPrice($product['price']);
            $this->setType($product['type']);
            $this->setWeight($product['value']);
        }

        $stmt->close();
        $conn->close();
    }

    public function deleteProduct($id,$conn){
        if ($conn->connect_error) {
            die('Connection Error: (' . $conn->connect_errno . ') ' . $conn->connect_error);
        }
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function makeJavascriptObject($id){
        return [
            'id' => $id,
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'value' => $this->getWeight(),
        ];
    }
}