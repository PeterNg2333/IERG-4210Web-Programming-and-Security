<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */
require __DIR__.'/admin/lib/db.inc.php';


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
  $purchase = $order -> {'purchase_units'}[0];
  $amount = $order -> {'purchase_units'}[0] -> {'amount'};
  $payer = $order -> {'payer'};
  $user = auth();

  if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $user ))
        throw new Exception("invalid-email");
  if (!preg_match('/^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/', $payer-> {'email_address'}))
        throw new Exception("invalid-email");
  if (!preg_match('/^[\w]+$/', $amount -> {'currency_code'}))
        throw new Exception("invalid-currency_code");
  if (!preg_match('/^[\d]+$/', (int)($amount -> {'value'})))
        throw new Exception("invalid-amount");
//   if (!preg_match('/^[\w]+$/', $purchase -> {'custom_id'}))
//       throw new Exception("invalid-custom_id");
//   if (!preg_match('/^[\w]+$/', $purchase -> {'invoice_id'}))
//       throw new Exception("invalid-invoice_id");

  $user = email_sanitization($user);
  $buyerEmail = email_sanitization($payer-> {'email_address'});
  $productList = json_encode($purchase -> {'items'});
  $currency = string_sanitization($amount -> {'currency_code'});
  $totalPrice = int_sanitization($amount -> {'value'});
  $paymentStatus = "Success";
  $customId = string_sanitization($purchase -> {'custom_id'});
  $invoiceId = string_sanitization($purchase -> {'invoice_id'});

  // $buyerEmail = $payer-> {'email_address'};
  // $productList = json_encode($purchase -> {'items'});
  // $currency = $amount -> {'currency_code'};
  // $totalPrice = $amount -> {'value'};
  // $paymentStatus = "Success";
  // header('Content-Type: text/html; charset=utf-8');
  // echo json_encode(array($buyerEmail, $currency, $totalPrice, $paymentStatus, $productList));
  // exit();

  return store_order($user, $productList, $currency, $totalPrice, $paymentStatus, $buyerEmail, $customId, $invoiceId);

  /* ========== REGION END ========== */
}

$json = file_get_contents("php://input");
$order = json_decode($json);
save_order($order);

?>