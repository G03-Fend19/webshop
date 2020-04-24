<?php

require_once './assets/header.php';

?>

<section class="confirmpage__container" style="padding-top: 2em; order: 1;">

  <h1>Thank you for your order!</h1>

  <table class="confirmtable">
    <thead class="confirmtable__thead">
      <tr>
        <th class="confirmtable__thead__productname">Product name</th>
        <th>Quantity</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody class="confirmtable__tbody">
      <!-- Här ska kod från localStorage in med JS -->
    </tbody>
  </table>

  <section class="confirm__price">
    <div class="confirm__price__shipping" style="display: flex; justify-content: flex-end; margin: 1em;">
      <strong style="margin-right: 1em;">Shopping fee</strong>
      <p>0 SEK</p>
    </div>
    <div class="confirm__price__total" style="display: flex; justify-content: flex-end; margin: 1em;">
      <strong style="margin-right: 1em;">Total price</strong>
      <p class="confirm__totalprice">2 099 SEK</p>
    </div>
  </section>

  <section class="confirm__shipping">
    <h2>It will be shipped to...</h2>
    <div class="confirm__shipping__customer">
      <h3>Customer</h3>
    </div>

    <div class="confirm__shipping__payment">
    </div>
  </section>
  <a href="index.php"><button>Shop more</button></a>

</section>
<script src="confirm-page.js"></script>

<?php
require_once './assets/foot.php';