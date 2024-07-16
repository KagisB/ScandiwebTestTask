<?php

?>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="styles/AddProduct.css">
</head>
<body>
<div id="header" >
    <div id="header-text">
        Product Add
    </div>
    <div id="header-buttons">
        <button id="save-product-btn">
            Save
        </button>
        <button id="cancel-product-btn">
            Cancel
        </button>
    </div>
</div>
<hr>
<div id="input-form">
<form id="product_form" action="../app/Router.php" method="POST">
    <div class="form-group">
        <label for="sku">
            SKU
        </label>
        <input id="sku" type="text" size="20">
    </div>
    <div class="form-group">
        <label for="name">
            Name
        </label>
        <input id="name" type="text" size="20">
    </div>
    <div class="form-group">
        <label for="price">
            Price
        </label>
        <input id="price" type="number" size="20" step="0.01">
    </div>
    <div class="form-group">
        <label for="productType">
            Type switcher
        </label>
        <select id="productType" >
            <option id="DVD" value="dvd" selected>DVD</option>
            <option id="Book" value="book">Book</option>
            <option id="Furniture" value="furniture">Furniture</option>
        </select>
    </div>
    <!--- Input, kurš atbilst tipam, ko izvēlējās---->
    <div id="special-value">

    </div>
</form>
</div>
</body>

<script>

    document.getElementById("save-product-btn").addEventListener('click', function(){
        addProduct();
    });
    document.getElementById("cancel-product-btn").addEventListener('click', function(){
        window.location.href="ProductList.php";
    });

    function addProduct(){
        let type = document.getElementById("productType").value;
        let specialValue = "";
        switch(type){
            case 'dvd':
                specialValue = document.getElementById("size").value;
                break;
            case 'book':
                specialValue = document.getElementById("weight").value;
                break;
            case 'furniture':
                specialValue = document.getElementById("height").value+"x"+document.getElementById("width").value+"x"+document.getElementById("length").value;
                break;
        }

        let product = {
            sku : document.getElementById("sku").value,
            name : document.getElementById("name").value,
            price : document.getElementById("price").value,
            type : type,
            value : specialValue,
        };

        if(product.sku && product.name && product.price && product.type && product.value){
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../app/Router.php?action=save', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.href="ProductList.php";
                } else if (xhr.readyState === 4) {
                    console.error('Error:', xhr.status, xhr.statusText);
                }
            };

            xhr.send(JSON.stringify(product));
        }
    }

    function updateLayoutForDVD() {
        let inputField = document.getElementById('special-value');

        let dvdDiv = document.createElement('div');
        dvdDiv.id = 'DVD';
        dvdDiv.className='form-group';

        let dvdLabel = document.createElement('label');
        dvdLabel.setAttribute('for', 'size');
        dvdLabel.textContent = 'Size(MB)';

        let dvdInput = document.createElement('input');
        dvdInput.setAttribute('type', 'number');
        dvdInput.setAttribute('id', 'size');
        dvdInput.setAttribute('step', 'any');

        let dvdParagraph = document.createElement('p');
        dvdParagraph.textContent = 'Please provide the size of the DVD in MB';

        dvdDiv.appendChild(dvdLabel);
        dvdDiv.appendChild(dvdInput);

        inputField.appendChild(dvdDiv);
        inputField.appendChild(dvdParagraph)
    }

    function updateLayoutForBook() {
        let inputField = document.getElementById('special-value');;

        let bookDiv = document.createElement('div');
        bookDiv.id = 'Book';
        bookDiv.className='form-group';

        let bookLabel = document.createElement('label');
        bookLabel.setAttribute('for', 'weight');
        bookLabel.textContent = 'Weight(KG)';

        let bookInput = document.createElement('input');
        bookInput.setAttribute('type', 'number');
        bookInput.setAttribute('id', 'weight');
        bookInput.setAttribute('step', 'any');

        let bookParagraph = document.createElement('p');
        bookParagraph.textContent = 'Please provide the weight of the Book in KG';

        bookDiv.appendChild(bookLabel);
        bookDiv.appendChild(bookInput);

        inputField.appendChild(bookDiv);
        inputField.appendChild(bookParagraph);
    }

    function updateLayoutForFurniture() {
        let inputField = document.getElementById('special-value');

        const furnitureDiv = document.createElement('div');
        furnitureDiv.id = 'Furniture';

        const heightFormGroup = document.createElement('div');
        heightFormGroup.classList.add('form-group');

        const heightLabel = document.createElement('label');
        heightLabel.setAttribute('for', 'height');
        heightLabel.textContent = 'Height(CM)';

        const heightInput = document.createElement('input');
        heightInput.setAttribute('type', 'number');
        heightInput.setAttribute('id', 'height');
        heightInput.setAttribute('step', 'any');

        heightFormGroup.appendChild(heightLabel);
        heightFormGroup.appendChild(heightInput);

        const widthFormGroup = document.createElement('div');
        widthFormGroup.classList.add('form-group');

        const widthLabel = document.createElement('label');
        widthLabel.setAttribute('for', 'width');
        widthLabel.textContent = 'Width(CM)';

        const widthInput = document.createElement('input');
        widthInput.setAttribute('type', 'number');
        widthInput.setAttribute('id', 'width');
        widthInput.setAttribute('step', 'any');

        widthFormGroup.appendChild(widthLabel);
        widthFormGroup.appendChild(widthInput);

        const lengthFormGroup = document.createElement('div');
        lengthFormGroup.classList.add('form-group');

        const lengthLabel = document.createElement('label');
        lengthLabel.setAttribute('for', 'length');
        lengthLabel.textContent = 'Length(CM)';

        const lengthInput = document.createElement('input');
        lengthInput.setAttribute('type', 'number');
        lengthInput.setAttribute('id', 'length');
        lengthInput.setAttribute('step', 'any');

        lengthFormGroup.appendChild(lengthLabel);
        lengthFormGroup.appendChild(lengthInput);

        const dimensionsParagraph = document.createElement('p');
        dimensionsParagraph.textContent = 'Please provide dimensions in HxWxL format';

        furnitureDiv.appendChild(heightFormGroup);
        furnitureDiv.appendChild(widthFormGroup);
        furnitureDiv.appendChild(lengthFormGroup);

        inputField.appendChild(furnitureDiv);
        inputField.appendChild(dimensionsParagraph);
    }

    const layoutUpdateMap = {
        "dvd": updateLayoutForDVD,
        "book": updateLayoutForBook,
        "furniture": updateLayoutForFurniture
    };

    window.addEventListener("load", () => {
        updateLayout();
    });
    document.getElementById("productType").addEventListener('change',updateLayout);

    function updateLayout() {
        let productType = document.getElementById("productType").value;
        const updateFunction = layoutUpdateMap[productType];
        if (updateFunction) {
            let inputField = document.getElementById('special-value');
            inputField.innerHTML = '';
            updateFunction();
        } else {
            console.log("Unknown product type: " + productType);
        }
    }


</script>
