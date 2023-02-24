<?php
require __DIR__.'/admin/lib/db.inc.php';
$c_res = ierg4210_cat_fetchall();
$p_res = ierg4210_prod_fetchAll();
if (!preg_match('/^\d*$/', $_GET['cid']))
    throw new Exception("invalid-cid");
$get_cid = (int) htmlspecialchars(($_GET['cid']));
$category = '';
$product = '';
$category_url = '';
$preload = "";
foreach ($c_res as $value){
    // $products .= '<li><a href = "'.$value["CID"].'"> '.$value["CATEGORIES_NAME"].'</a></li>';
    $category .= '<il><a href="main.php?cid='.$value["CID"].'" id="cid-'.$value["CID"].'" class="list-group-item list-group-item-action">'.$value["CATEGORY_NAME"].'</a></il>';
}
$category .= '';


if ($get_cid == null || $get_cid == 0){
    $preload .= '<div id="preloader" class="container">';
    $preload .= '    <div id="loadingImg" class="row">';
    $preload .= '        <img src="./Resource/loading-gif.gif"/>';
    $preload .= '        <h5> Loading...... </h5>';
    $preload .= '    </div>';
    $preload .= '</div>';
    $p_res = ierg4210_prod_fetchAll();
    $category_url .= '<span id="CatergoryPath">> You might like it</span>';
}
else{
    $p_res = ierg4210_prod_fetchAll_by_cid_page($get_cid);
//     $cName_res = ierg4210_cat_fetch_by_cid_page($get_cid);
//     foreach ($cName_res as $value){
//         $category_url .= '<span id="CatergoryPath">> <a href="/main.php/?cid='.$value["CID"].'"> '.$value["CATEGORY_NAME"].' </a></span>';
//     }
}
foreach ($p_res as $value){
    // $products .= '<li><a href = "'.$value["CID"].'"> '.$value["CATEGORIES_NAME"].'</a></li>';
    $product .= '<div class="col-lg-3 mb-3 px-0" id="P-'.$value["PID"].'">';
    $product .= '    <div class="card mx-2 product_card_display">';
    $product .= '        <a href="./?pid='.$value["PID"].'"><img class="card-img-top" src="./admin/lib/images/P'.$value["PID"].'.jpg" alt="'.$value["PRODUCT_NAME"].'"></a>';
    $product .= '        <div class="card-body card_display_body row">';
    $product .= '           <div class="row">';
    $product .= '               <h5 class="card-title col-9"><a href="./?pid='.$value["PID"].'">'.$value["PRODUCT_NAME"].'</a></h5>';
    $product .= '               <p class="card-text col-3">$15</p>';
    $product .= '           </div>';
    $product .= '           <button type="button" class="btn btn-primary btn-block product_card_display_button"> Add to Shopping Cart</button>';
    $product .= '        </div>';
    $product .= '   </div>';
    $product .= '</div>';
}
$product .='';


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Mall4210.com</title>
        <link rel="icon" type="image/x-icon" href="Resource/Mall_icon.jpg"/>
        <!-- Styling -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="CSS/index.css">
        <link rel="stylesheet" type="text/css" href="CSS/main.css">
    </head>
    <body>
        <!-- Preload-->
        <?php echo $preload ?>

        <!-- Header -->
        <header id="header" class="container-fiuld d-none">
            <?php 
                $header_html = file_get_contents('./Snippet/Header.html');
                echo $header_html;
            ?>
        </header>

        <!-- Main Content -->
        <main id="main" class="container d-none">
            <?php 
                $main_html = file_get_contents('./Snippet/Main.html');
                $main_html = str_replace('%category_list%', $category, $main_html);
                $main_html = str_replace('%product_list%', $product, $main_html);
                $main_html = str_replace('%CatergoryPath%', $category_url, $main_html);
                $main_html = str_replace('<!--?PHP--> ', '', $main_html);
                echo $main_html;
            ?>
        </main>

        <!-- Footer -->
        <footer id="footer" class="container-fiuld d-none">
            <?php 
                $Footer_html = file_get_contents('./Snippet/Footer.html');
                echo $Footer_html;
            ?>
        </footer>

        <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
        <script src="JavaScript/utity.js"></script>
        <script src="JavaScript/index.js"></script>
    </body>
</html>
