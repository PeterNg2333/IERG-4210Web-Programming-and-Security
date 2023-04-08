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
        <link rel="stylesheet" type="text/css" href="../CSS/admin_order.css">
    </head>
        
    <body>
        <!-- Header -->
        <header id="header" class="container-lg">
            <?php 
                $header_html = file_get_contents('./Snippet_admin/header_admin.html');
                $header_html = str_replace('%Admin%', email_sanitization($auth), $header_html);
                $header_html = str_replace('order_admin.php', "admin.php", $header_html);
                $header_html = str_replace('Order', "Panel", $header_html);
                $header_html = str_replace('fas fa-history', "fa-solid fa-unlock-keyhole", $header_html);
                echo $header_html;
            ?>
        </header>
        <main id="main" class="container-lg mt-5">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">USER</th>
                    <th scope="col">INVOICE_ID</th>
                    <th scope="col">CUSTOM_ID</th>
                    <th scope="col">$TOTAL&nbsp;PRICE</th>
                    <th scope="col">PRODUCT LIST</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">BUYER PAYPAL</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $order = order_fetchAll();
                foreach ($order as $value){
                    $oid = int_sanitization($value["OID"]);
                    $userEmail = email_sanitization($value["USER"]);
                    $productList = json_decode($value["PRODUCTLIST"]);
                    $currency = string_sanitization($value["CURRENCY"]);
                    $totalPrice = int_sanitization($value["TOTALPRICE"]);
                    $paymentStatus = string_sanitization($value["PAYMENT_STATUS"]);
                    $buyerEmail = email_sanitization($value["BUYER_EMAIL"]);
                    $customId = string_sanitization($value["CUSTOM_ID"]);
                    $invoiceId = string_sanitization($value["INVOICE_ID"]);
                    echo' <tr class="table_row">';
                    echo'   <th scope="row">'.$oid.'</th>';
                    echo'   <td>'.$userEmail.'</td>';
                    echo'   <td>'.$invoiceId.'</td>';
                    echo'   <td>'.$customId.'</td>';
                    echo'   <td class="total_price">'.$currency."$&nbsp;".$totalPrice.'</td>';
                    echo'   <td>';
                    echo '      <ul class="list-group">';
                            foreach ($productList as $item){
                                $item_name = string_sanitization($item->{'name'});
                                $item_price = int_sanitization($item->{'unit_amount'}->{'value'});
                                $item_quantity = int_sanitization($item->{'quantity'});
                                $item_name = str_replace(" ", "&nbsp", $item_name);
                                echo '<il class="mb-1 items"><span class="item_name text-primary">•&nbsp'.$item_name.':&nbsp'.'</span>'.$currency.'$<span class="item_value">'.$item_price."*".$item_quantity.'</span>&nbsp</il>';
                            }
                    echo '      </ul>';
                    echo'   </td>';
                    echo'   <td>'.'<span class="badge badge-pill badge-info bg-info">'.$paymentStatus.'</span></td>';
                    echo'   <td>'.$buyerEmail.'</td>';

                    echo' </tr>';
                    // echo json_encode($value);
                }
                
            ?>
            </tbody>

                

            </table>
        </main>

    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../JavaScript/utity.js"></script>
    <script src="./index.js"></script>
    </body>
</html>