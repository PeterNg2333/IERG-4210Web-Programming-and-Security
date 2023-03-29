<?php
require __DIR__.'/lib/db.inc.php';
$auth=auth();
if($auth==false){
    header('Location: login_admin.php',true,302);
}
$res_cat_fetchall = ierg4210_cat_fetchall();
$res_number = ierg4210_prod_and_cat_count();
$options = '';

foreach ($res_cat_fetchall as $value){
    $options .= '<option value="'.$value["CID"].'"> '.$value["CATEGORY_NAME"].' </option>';
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Inventroy4210</title>
        <link rel="icon" type="image/x-icon" href="../Resource/Admin_Panel_Icon.avif"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="../CSS/index.css">
        <link rel="stylesheet" type="text/css" href="../CSS/admin.css">
    </head>
        
    <body>
        <!-- Preload-->
        <div id="preloader" class="container">
            <div id="loadingImg" class="row">
                <img src="../Resource/AdminPage_loading.gif"/>
                <h5> Admin Panel Loading...... </h5>
            </div>
        </div>
        <!-- Header -->
        <header id="header" class="container-lg d-none">
            <?php 
                $header_html = file_get_contents('./Snippet_admin/header_admin.html');
                echo $header_html;
            ?>
        </header>

        <!-- Main Content -->
        <main id="main" class="container mt-3 d-none">
            <?php 
            
                $main_html = file_get_contents('./Snippet_admin/main_admin.html');
                $main_html = str_replace('%Categories_NUM%', $res_number[0]["CATEGORY_NUM"], $main_html);
                $main_html = str_replace('%Products_NUM%', $res_number[0]["PRODUCT_NUM"], $main_html);
                $main_html = str_replace('%Out of Stock_NUM%', $res_number[0]["OUT_OF_STOCK"], $main_html);
                $main_html = str_replace('%category_options%', $options, $main_html);
                $main_html = str_replace('%prod_insert_nonce%', csrf_getNonce("prod_insert"), $main_html);
                $main_html = str_replace('%cat_insert_nonce%', csrf_getNonce("cat_insert"), $main_html);
                $main_html = str_replace('%cat_edit_nonce%', csrf_getNonce("cat_edit"), $main_html);
                $main_html = str_replace('%cat_delete_nonce%', csrf_getNonce("cat_delete"), $main_html);
                $main_html = str_replace('%prod_delete_nonce%', csrf_getNonce("prod_delete"), $main_html);
                $main_html = str_replace('%prod_edit_nonce%', csrf_getNonce("prod_edit"), $main_html);

                
                

                $main_html = str_replace('<!--?PHP--> ', '', $main_html);
                
                echo $main_html;
            ?>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
        <script src="../JavaScript/utity.js"></script>
        <script src="./index.js"></script>
    </body>
</html>

<!-- <html>
    <fieldset>
        <legend> New Product version 1.2 </legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
            <label for="prod_cid"> Category *</label>
            <div> <select id="prod_cid" name="cid"> <php echo $options; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_inv" type="text" name="price" required="required" pattern="^[\d+]+$"/></div>
            <label for="prod_inv"> Inventory </label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
            <label for="prod_desc"> Description *</label>
            <div> <input id="prod_desc" type="text" name="description"/> </div>
            <label for="prod_image"> Image * </label>
            <div> <input type="file" name="file" required="true" accept="image/jpeg"/> </div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html> -->
