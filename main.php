<?php
require __DIR__.'/admin/lib/db.inc.php';
$c_res = ierg4210_cat_fetchall();
// $p_res = ierg4210_prod_fetchAll();
$user = email_sanitization(auth());
if ($user == false) {
    $user = "Guest";
}


if (!preg_match('/^\d*$/', $_GET['cid']))
    throw new Exception("invalid-cid");
$get_cid = int_sanitization(($_GET['cid']));
if (!preg_match('/^\d*$/', $_GET['pid']))
    throw new Exception("invalid-pid");
$get_pid = int_sanitization(($_GET['pid']));
$category = '';
$product = '';
$category_url = '';
$preload = "";
foreach ($c_res as $value){
    $cid = string_sanitization($value["CID"]);
    $c_name = string_sanitization($value["CATEGORY_NAME"]);
    $category .= '<il><a href="main.php?cid='.$cid.'" id="cid-'.$cid.'" class="list-group-item list-group-item-action">'.$c_name.'</a></il>';
}
$category .= '';

$category_url .= '<span id="CatergoryPath">> <span id="cPathRemove"> You might like it</span></span>';

if (($get_cid == null || $get_cid == 0) && ($get_pid == null || $get_pid == 0)){
    $preload .= '<div id="preloader" class="container">';
    $preload .= '    <div id="loadingImg" class="row">';
    $preload .= '        <img src="./Resource/loading-gif.gif"/>';
    $preload .= '        <h5> Loading...... </h5>';
    $preload .= '    </div>';
    $preload .= '</div>';
    // $p_res = ierg4210_prod_fetchAll();
    $p_res = ierg4210_prod_fetchAll();
}
else{
    $p_res = ierg4210_prod_fetchAll_by_cid_page($get_cid);
    $cName_res = ierg4210_cat_fetch_by_cid_page($get_cid);
    foreach ($cName_res as $value){
        $cid = string_sanitization($value["CID"]);
        $c_name = string_sanitization($value["CATEGORY_NAME"]);
        $category_url = '<span id="CatergoryPath">> <a id="cPathRemove" href="/main.php?cid='.$cid.'"> '.$c_name.' </a></span>';
    }
}

if ($get_pid == null || $get_pid == 0){
    $count = 0;
    foreach ($p_res as $value){
        if ($count < 6){

            // $products .= '<li><a href = "'.$value["CID"].'"> '.$value["CATEGORIES_NAME"].'</a></li>';
            $product .= '<div class="count_product_loaded col-lg-3 col-md-6 mb-3 px-0" id="P-'.string_sanitization($value["PID"]).'">';
            $product .= '    <div class="card mx-2 product_card_display">';
            $product .= '        <a href="/main.php?pid='.string_sanitization($value["PID"]).'" class="product_detail_button">';
            $product .= '             <img class="card-img-top" src="./admin/lib/images/P'.string_sanitization($value["PID"]).'.jpg" alt="'.string_sanitization($value["PRODUCT_NAME"]).'" id="imageP-'.string_sanitization($value["PID"]).'">';
            $product .= '        </a>';
            $product .= '        <div class="card-body card_display_body row">';
            $product .= '           <div class="row">';
            $product .= '               <h5 class="product_detail_button card-title col-8"><a href="/main.php?pid='.string_sanitization($value["PID"]).'" id="titleP-'.string_sanitization($value["PID"]).'">'.string_sanitization($value["PRODUCT_NAME"]).'</a></h5>';
            $product .= '               <p class="card-text col-4">$'.string_sanitization($value["PRICE"]).'</p>';
            $product .= '           </div>';
            $product .= '           <input id="addToCartNum-'.string_sanitization($value["PID"]).'" type=hidden value="1" />';
            $product .= '           <button type="button" id="addToCart-'.string_sanitization($value["PID"]).'" onclick="addToCart_button(event)" class="addToCart btn btn-primary btn-block product_card_display_button"> Add to Shopping Cart</button>';
            $product .= '        </div>';
            $product .= '   </div>';
            $product .= '</div>';

            $count ++;
        }
        else{
            break;
        }
    }
}
else{
    $product .= "";
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
                $header_html = str_replace('%User%', $user, $header_html);
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
                $main_html = str_replace('%check_out_nonce%', string_sanitization(csrf_getNonce("check_out")), $main_html);
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

        <script src="https://www.paypal.com/sdk/js?client-id=<?php echo json_decode(file_get_contents("secret.json"))->client_id; ?>"></script>
        <script src="JavaScript/cart.js"></script>
        <script>
            paypal.Buttons({
            /* Sets up the transaction when a payment button is clicked */
            createOrder: async (data, actions) => {
                /* [TODO] create an order from localStorage */
                // Extra check //
                if (document.querySelector(".account_name ").innerText === " Guest"){
                    alert("Please login before purchase");
                    window.location.href = "admin/login_admin.php";
                    return false;
                }
                // Sample code
                let order_details = await fetch("create_order.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(getCartItems(), null, 2)
                }).then(response => response.json());

                // console.log(order_details);

                return actions.order.create(order_details);
            },

            /* Finalize the transaction after payer approval */
            onApprove: async (data, actions) => {
                return actions.order.capture()
                .then(async orderData => {
                    /* Successful capture! For dev/demo purposes: */
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

                    await fetch('save_order.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(orderData, null, 2)
                    });
                    
                    clearCart(); // Clear the web shop cart
                    alert("Purchase Success!! Go back to main page now !");
                    window.location.href = "main.php"; // Redirect to another page
                });
            },
            }).render('#paypal-button-container');
        </script>
    </body>
</html>
