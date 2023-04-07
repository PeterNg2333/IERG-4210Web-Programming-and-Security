/**
 * This function returns all items in the cart. 
 * @return an array containing items (pid + quantity)
 */
function getCartItems() {
  /* @TODO Comment out the current return statement */
  /* ========== REGION START ========== */
  // return [
  //   {
  //     pid: 1, quantity: 1,
  //   },
  //   {
  //     pid: 2, quantity: 2
  //   }
  // ];
  /* ========== REGION END ========== */

  /* @TODO Your Implementation Here. */
  /* ========== REGION START ========== */
  console.log(localStorage.getItem('shoppingList'))
  return JSON.parse(localStorage.getItem('shoppingList'));


  /* ========== REGION END ========== */
}

/**
 * This function clears all items in the cart. 
 */
function clearCart() {
  /* @TODO Your Implementation Here. */
  /* ========== REGION START ========== */
  localStorage.clear();


  /* ========== REGION END ========== */ 
}
