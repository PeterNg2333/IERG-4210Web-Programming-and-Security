<?php
require __DIR__.'/lib/db.inc.php';
$auth=auth();
if($auth==false ){
    header('Location: login_admin.php',true,302);
}
    // echo is_admin($auth);
    // exit();
if (is_admin($auth) == false){
    echo "Please login in with Admin account";
    exit();
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
        <!-- Header -->
        <header id="header" class="container-lg">
            <?php 
                $header_html = file_get_contents('./Snippet_admin/header_admin.html');
                $header_html = str_replace('%Admin%', email_sanitization($auth), $header_html);
                $header_html = str_replace('order_admin.php', "order_admin.php", $header_html);
                $header_html = str_replace('Order', "Panel", $header_html);
                $header_html = str_replace('fas fa-history', "fa-solid fa-unlock-keyhole", $header_html);
                echo $header_html;
            ?>
        </header>
        <main id="main">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </main>

    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../JavaScript/utity.js"></script>
    </body>
</html>