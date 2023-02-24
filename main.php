<?php
require __DIR__.'/admin/lib/db.inc.php';
$res = ierg4210_cat_fetchall();

$category = '';

foreach ($res as $value){
    // $products .= '<li><a href = "'.$value["CID"].'"> '.$value["CATEGORIES_NAME"].'</a></li>';
    //
    
    $category .= '<il><a href="'.$value["CID"].'" id="cid-'.$value["CID"].'" class="list-group-item list-group-item-action">'.$value["CATEGORIES_NAME"].'</a></il>';
}

$category .= '';

// echo '<div id = "maincontent">
// <div id = "products">'.$products.'
// </div>
// </div>';

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
        <div id="preloader" class="container">
            <div id="loadingImg" class="row">
                <img src="./Resource/loading-gif.gif"/>
                <h5> Loading...... </h5>
            </div>
        </div>

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
