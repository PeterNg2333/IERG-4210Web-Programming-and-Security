<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */
// require __DIR__.'/admin/lib/db.inc.php';


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
  $payer = $order -> {'payer'};


  header('Content-Type: text/html; charset=utf-8');
  echo json_encode($payer);
  exit();
  /* ========== REGION END ========== */
}

$json = file_get_contents("php://input");
$order = json_decode($json);
save_order($order);

?>