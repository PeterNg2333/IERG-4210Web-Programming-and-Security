<?php
require __DIR__.'/admin/lib/db.inc.php';
$c_res = ierg4210_cat_fetchall();
// $p_res = ierg4210_prod_fetchAll();
$user = email_sanitization(auth());
if ($auth = false) {
    $user = "Guest";
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>My Orders</title>
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

        <!-- Header -->
        <header id="header" class="container-fiuld d-none">
            <?php 
                $header_html = file_get_contents('./Snippet/Header.html');
                $header_html = str_replace('%User%', $user, $header_html);
                echo $header_html;
            ?>
        </header>
        <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
        <script src="JavaScript/utity.js"></script>
        <script src="JavaScript/index.js"></script>
    </body>