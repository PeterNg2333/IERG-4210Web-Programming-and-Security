
<?php
    require __DIR__.'../lib/db.inc.php';
    $res = ierg4210_cat_fetchall();
    $options = '';
    foreach ($res as $value){
        $options .= '<option value="'.$value["CID"].'"> '.$value["CATEGORIES_NAME"].' </option>';
    }
    $a = 123;
?>

<!-- Title and add/delete categories button  -->
<section class="row mb-1" id="title_and_button">
    <h4 class="col-7 ms-5 "></h4>


    <form class="col-4 navbar navbar-expand-lg mx-2">

        <input type="text" placeholder="Enter Category Name">
        <button type="button" class="btn btn-primary btn-sm  mx-1 nav-item">Add Category</button>
        <button type="button" class="btn btn-danger btn-sm mx-1 nav-item">Delete Category</button>
    </form>
</section>

<!-- Dashboard-->
<section class="row mt-3">
    <div class="col-1"></div>
    <div class="col-3">
        <h6>Categories</h6>
        <h4 class="text-primary">6</h4>
    </div>
    <div class="col-3">
        <h6>Total Products</h6>
        <h4 class="text-primary">50</h4>
    </div>
    <div class="col-3">
        <h6>Out of Stock</h6>
        <h4 class="text-primary">2</h4>
    </div>
    <div class="col-1"></div>
</section>


<!-- Filter -->
<section class="row mb-1 mt-5 ms-5">
    <!-- Category-->
    <form class="col-8">
        <label for="Category_dropDown">Category: </label>
        <select id="Category_dropDown" class="text-primary">
            <option value="All">All</option>
            <?php echo $options; ?>
        </select>
    </form>

    <!-- Sorting -->
    <form class="col-3 ">
        <label class="ps-3" for="Sort_by">Sort:</label>
        <select id="Sort_by" class="text-primary">
            <option value="All">Product Name</option>
            <option value="asdas">Price</option>
            <option value="asdas">Inventory</option>
        </select>

        <label class="ps-3" for="Order"> by: </label>
        <select id="Order" class="text-primary">
            <option value="All">A-Z</option>
            <option value="asdas">Z-A</option>
        </select>
    </form>
</section>

<!-- Table of product -->
<section class="mt-3 ms-5  bg-light">
    
        <!--List column-->
    <h5 class="row bg-light" id="admin_product_list_header mx-3 ">
        <div class="col-2 p-2 ps-4">Product Name</div>
        <div class="col-2 p-2 ps-3">| Price </div>
        <div class="col-2 p-2 ps-3">| Inventory</div>
        <div class="col-3 p-2 ps-3">| Details</div>
        <div class="col-1 p-2 ps-2">| Image</div>
        <div class="col-2 p-2 ps-3"> </div>
    </h5>

    <ul class="container" id="list_of_record">
        <!-- Empty List-->
        <il id="product_input" class=" mb-2">
            <form class="form-group row" id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
                <div class="col-2">
                    <input id="product_name_input" type="text" placeholder="Name">
                </div>
                <div class="col-2">
                    <input id="product_name_input" type="number" placeholder="Price $">
                </div>
                <div class="col-2">
                    <input id="product_name_input" type="number" placeholder="Stock">
                </div>
                <div class="col-3">
                    <textarea id="product_name_input" type="textarea" cols="35" rows="5" placeholder="..."></textarea>
                </div>
                <div class="col-2">
                    <input id="product_name_input" type="file" placeholder="">
                    <div class="image_uploaded_display">
                        <p> Image preview : </p>
                        <img class="image_uploaded_display" src="../Resource/Shoes.jpeg" alt="Girl in a jacket">
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-primary btn-sm mb-1 mx-1 nav-item">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm  mb-1 mx-1 nav-item">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </form>
        </il>
    </ul>
</section>