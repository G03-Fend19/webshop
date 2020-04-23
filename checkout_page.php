<?php
require_once 'assets/header.php';
?>

<style>
.order-summary__img {
  object-fit: cover;
  height: 40px;
  width: 40px;
}

.order-summary {
  width: 100vw;
}

input[type="number"] {
  -moz-appearance: textfield;
  width: 15px;
  background-color: transparent;
  border: 0px solid;
  border-radius: 25%;
  box-shadow: none;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;

}

.qty-btn {
  border-radius: 50%;
  background-color: Transparent;
  background-repeat: no-repeat;
  border: none;
  cursor: pointer;
  overflow: hidden;
  outline: none;
}
</style>


<section id="pTable-section" class="product-section">


</section>

</main>






<script src="./checkout.js"></script>

<?php
require_once 'assets/foot.php';

?>