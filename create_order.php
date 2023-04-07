<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */



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
  $json = [];
  return $cart;


  // $order = json_decode($json);

  // $order->purchase_units[0]->custom_id = gen_digest(array($order->purchase_units[0]->amount->currency_code));
  // $order->purchase_units[0]->invoice_id = gen_uuid(); // invoice_id must be unique to avoid crashes.

  // return json_encode($order);
  /* ========== REGION END ========== */
}


$json = file_get_contents("php://input");
$cart = json_decode($json);
echo create_order($cart);

?>
