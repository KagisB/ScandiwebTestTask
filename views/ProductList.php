<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Repositories\ProductRepository;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$productRepository = new ProductRepository("localhost","root","","scandiwebtask",3306);
$productList = $productRepository->getAllProducts();
/*foreach($productList as $product){
    echo($product->displayProduct());
}*/
?>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="styles/ProductList.css">
</head>
<body>


<div id="header">
    <div id="header-text">
        Product List
    </div>
    <div id="header-buttons">
        <button id="add-product-btn"> <!-- Move user to add product page--->
            Add
        </button>
        <button id="delete-product-btn"> <!---- On click - collect all inputs, where button has been pressed for delete, and delete those products--->
            Mass delete
        </button>
    </div>
</div>
<hr>
<div id="product-list"><!-- Should have flex, 4 boxes per column--->

</div>

</body>


<!--- How does the div for each product look
<div id="productX" class="product-box">
    <input type="checkbox" id="delete-button-X" class="delete-checkbox">
    <input type="hidden" id="productID" value="">
    <p>SKU</p>
    <p>Name</p>
    <p>Price</p>
    <p>Product specific value(size,weight,dimensions)</p>
</div>---->
<script>

    //document.getElementById('add-product-btn').addEventListener('onclick', redirectToAddProduct);

    document.getElementById('add-product-btn').onclick = function(){
        console.log('redirect');
        window.location.href = 'AddProduct.php';
    };
    document.getElementById('delete-product-btn').onclick = function(){
        deleteProducts();
    };

    function redirectToAddProduct(){
        console.log('redirect');
        window.location.href = 'AddProduct.php';
    }

    function createProductDiv(product){
        let productList = document.getElementById('product-list');
        let productDiv = document.createElement('div');
        productDiv.id = 'product'+product.id;
        productDiv.className = 'product-box';

        let checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = 'delete-button-X';
        checkbox.className = 'delete-checkbox';

        let hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.id = 'productID';
        hiddenInput.value = product.id;

        let skuParagraph = document.createElement('p');
        skuParagraph.textContent = product.sku;

        let nameParagraph = document.createElement('p');
        nameParagraph.textContent = product.name;

        let priceParagraph = document.createElement('p');
        priceParagraph.textContent = product.price;

        let specificValueParagraph = document.createElement('p');
        specificValueParagraph.textContent = product.value;

        productDiv.appendChild(checkbox);
        productDiv.appendChild(hiddenInput);
        productDiv.appendChild(skuParagraph);
        productDiv.appendChild(nameParagraph);
        productDiv.appendChild(priceParagraph);
        productDiv.appendChild(specificValueParagraph);

        productList.appendChild(productDiv);
    }

    function deleteProducts(){
        //Collect ids of divs that have
        let productBoxes = document.querySelectorAll('.product-box');
        let selectedIds = [];
        //console.log(productBoxes);
        productBoxes.forEach(box => {
            // Get the checkbox within the current div
            let checkbox = box.querySelector('.delete-checkbox');

            // Check if the checkbox is checked
            if (checkbox && checkbox.checked) {
                // Get the hidden input within the current div
                let hiddenInput = box.querySelector('#productID');
                if (hiddenInput) {
                    selectedIds.push(hiddenInput.value);
                }
            }
        });
        //console.log(selectedIds);
        if(selectedIds !== []){
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../app/Router.php?action=delete', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    //const response = JSON.parse(xhr.responseText);
                    //console.log('Success:');
                    location.reload();
                } else if (xhr.readyState === 4) {
                    console.error('Error:', xhr.status, xhr.statusText);
                }
            };

            xhr.send(JSON.stringify(selectedIds));
        }
    }

    let products = <?php echo json_encode($productList); ?>;
    //let products = <?php //echo json_encode($productListRaw); ?>;
    for(let product of products){
        //console.log(product);
        createProductDiv(product);
    }
    //console.log(products);
    //console.log(products[0].id);
</script>
<?php

?>