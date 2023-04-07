<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */
require __DIR__.'/admin/lib/db.inc.php';

/* ========== REGION END ========== */

/**
 * This function returns a digest based on a list of variables.
 * @return a string denoted digest
 */
function gen_digest($array)
{
  $digest = hash("sha256", implode(";", $array));
  return $digest;
}

/**
 * This function returns a UUID.
 * @return a string denoted UUID 
 * @see https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
 */
function gen_uuid()
{
  $data = random_bytes(16);
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Returns an valid order with digest and invoice.
 * @param an object representing items in cart (pid + quantity)
 * @return a string representing the valid order
 */
function create_order($cart)
{
  /* @TODO Comment out the current return statement */
  /* ========== REGION START ========== */
  // $json = '{
  //   "purchase_units": [
  //     {
  //       "amount": {
  //         "currency_code": "USD",
  //         "value": 11,
  //         "breakdown": {
  //           "item_total": {
  //             "currency_code": "USD",
  //             "value": 11
  //           }
  //         }
  //       },
  //       "items": [
  //         {
  //           "name": "1:ProductA",
  //           "unit_amount": {
  //             "currency_code": "USD",
  //             "value": 1
  //           },
  //           "quantity": 1
  //         },
  //         {
  //           "name": "2:ProductB",
  //           "unit_amount": {
  //             "currency_code": "USD",
  //             "value": 2
  //           },
  //           "quantity": 5
  //         }
  //       ]
  //     }
  //   ]
  // }';
  

  // $order = json_decode($json);

  // $order->purchase_units[0]->custom_id = gen_digest(array($order->purchase_units[0]->amount->currency_code));
  // $order->purchase_units[0]->invoice_id = gen_uuid(); // invoice_id must be unique to avoid crashes.

  // return json_encode($order);
  /* ========== REGION END ========== */

  /* @TODO Your Implementation here */
  /* ========== REGION START ========== */
  $order_value = 0;
  $items = array();
  $salt = mt_rand()*mt_rand();
  foreach($cart as $item){
    if (!preg_match('/^[\d]+$/', $item->id))
      throw new Exception("invalid-pid");
    if (!preg_match('/^[\d]+$/', $item->orderAmount))
      throw new Exception("invalid-quantity");

    $pid = int_sanitization($item->id);
    $product = get_prod_by_pid($pid)[0];

    $product_name = string_sanitization($product["PRODUCT_NAME"]);
    $product_price = int_sanitization($product["PRICE"]);
    $quantity = int_sanitization($item->orderAmount);

    $temp = new stdClass();
    $temp->name = $product_name;
    $temp->unit_amount->currency_code = "USD";
    $temp->unit_amount->value = $product_price ;
    $temp->quantity = $quantity;
    array_push($items, json_decode(json_encode($temp)));
    $order_value = $order_value + ($product_price*$quantity);
  }

  $order = json_decode(array("purchase_units" => []));
  $order -> purchase_units[0]->amount->currency_code = "USD";
  $order -> purchase_units[0]->amount->value = $order_value;
  $order -> purchase_units[0]->amount->breakdown->item_total->currency_code = "USD";
  $order -> purchase_units[0]->amount->breakdown->item_total->value = $order_value;
  $order -> purchase_units[0]->items = $items;
  $order->purchase_units[0]->custom_id = gen_digest(array(json_encode($order), $salt, "sb-ywvit25424931@business.example.com"));
  $order->purchase_units[0]->invoice_id = gen_uuid(); // invoice_id must be unique to avoid crashes.

  return json_encode($order);
  /* ========== REGION END ========== */
}


$json = file_get_contents("php://input");
$cart = json_decode($json);
echo create_order($cart);

?>
