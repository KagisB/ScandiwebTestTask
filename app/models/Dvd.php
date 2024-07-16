<?php

namespace App\models;

use mysqli;

class Dvd extends Product
{
    private $size;

    public function __construct($sku, $name, $price, $type, $value)
    {
        //parent::__construct($id, $sku, $name, $price, $type);
        parent::__construct($sku, $name, $price, $type);
        $this->setSize($value);
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function displayProduct()
    {
        echo "Bvd: " . $this->getName() . ", Price: " . $this->getPrice() . ", Sku: " . $this->getSku() . ", Size(MB): " . $this->getSize();
    }

    public function saveProduct($conn)
    {
        if ($conn->connect_error) {
            die('Connection Error: (' . $conn->connect_errno . ') ' . $conn->connect_error);
        }

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $type = $this->getType();
        $size = $this->getSize();

        $stmt1 = $conn->prepare("SELECT * from products where sku = ?");
        $stmt1->bind_param('s',$sku);
        $stmt1->execute();
        $result = $stmt1->get_result();

        if ($result->fetch_assoc()) {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, type = ?, value = ? WHERE sku = ?");
            $stmt->bind_param('sdsds', $name, $price, $type, $size, $sku);
        } else {
            // Insert new product
            $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, value) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssdss',$sku, $name, $price, $type, $size);
        }

        $stmt->execute();

        if (!$this->getId()) {
            // If it's a new product, set the ID after insertion
            $this->setId($conn->insert_id);
        }

        $stmt->close();
        //$conn->close();
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
            $this->setSize($product['value']);
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
            'value' => $this->getSize(),
        ];
    }
}