<?php
require_once 'assets/header.php';
?>
<main class="checkout">
  <section id="pTable-section" class="product-section">


  </section>

</main>





<script>
let order = JSON.parse(localStorage.cart);
const productSection = document.querySelector('#pTable-section');
console.log(productSection);

if (order) {
  let productTable = `<table>
                        <thead>
                          <tr>
                            <th></th>
                            <th></th>
                            <th>Product name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                          </tr>
                        </thead>
                        `;

  /*   <tbody>

</tbody>
</table> */
} else {
  /* <h4>No products in this category</h4> */
}
console.log(order);
</script>



<?php
require_once 'assets/foot.php';

?>