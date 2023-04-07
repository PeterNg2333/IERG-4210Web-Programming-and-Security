<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */



/* ========== REGION END ========== */

/**
 * This function saves the order into the database.
 * @param order an object containing order details
 */
function save_order($order) {
  /* @TODO Comment out the current return statement */
  /* ========== REGION START ========== */
  file_put_contents("order.json", json_encode($order, JSON_PRETTY_PRINT));

  /* ========== REGION END ========== */

  /* @TODO Your Implementation Here. */
  /* ========== REGION START ========== */
  $user = auth();
  $purchase = $order -> {'purchase_units'}[0];
  $payer = $order -> {'payer'};
  if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $user ))
        throw new Exception("invalid-email");
  if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $payer-> {'email_address'}))
        throw new Exception("invalid-email");
  if (!preg_match('/^[\w]+$/', $payer-> {'amount'} -> {'currency_code'}))
        throw new Exception("invalid-currency_code");
  if (!preg_match('/^[\d]+$/', $payer-> {'amount'} -> {'value'}))
        throw new Exception("invalid-amount");

  $user = string_sanitization($user);
  $buyerEmail = string_sanitization($payer-> {'email_address'});
  $productList = string_sanitization(json_encode($purchase -> {'items'}));
  $currency = string_sanitization($payer-> {'amount'} -> {'currency_code'});
  $totalPrice = int_sanitization($payer-> {'amount'} -> {'value'});
  $paymentStatus = string_sanitization("Success");
  
  echo json_encode($productList);




  /* ========== REGION END ========== */
}

$json = file_get_contents("php://input");
$order = json_decode($json);
save_order($order);

?>