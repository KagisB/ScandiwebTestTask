<?php

namespace App\models;

abstract class Product
{

    protected $id;
    protected $name;
    protected $price;
    protected $sku;
    protected $type;

    public function __construct($sku, $name, $price, $type)
    {
        //$this->setId($id);
        $this->setName($name);
        $this->setPrice($price);
        $this->setSku($sku);
        $this->setType($type);
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setSku($description)
    {
        $this->sku = $description;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getType()
    {
        return $this->type;
    }

    abstract public function displayProduct();

    abstract public function saveProduct($conn);

    abstract public function loadProduct($id,$conn);

    abstract public function makeJavascriptObject($id);

    abstract public function deleteProduct($id,$conn);
}
