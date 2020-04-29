<?php
require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";

$headline = "Orders";
if(isset($_GET['orders'])) {
$headline = $_GET['orders'] . " orders";
};

?>
<main class="admin__products">

  <div class="admin__products__text">
    <h1 class="headline__php"><?php echo ucfirst($headline)?></h1>
    <a href="./create_product.php">
  </div>
  <?php
  if(isset($_GET['orders'])) {
    if($_GET['orders'] == "active") {
      require_once "./assets/get_active_orders.php";
    } elseif ($_GET['orders'] == "completed") {
      require_once "./assets/get_completed_orders.php";
    } else {
      require_once "./assets/get_active_orders.php";
      require_once "./assets/get_completed_orders.php";
    }
  } else {
    echo "<h2 class='title__orders'>Active orders</h2>";
    require_once "./assets/get_active_orders.php";
    echo "<h2 class='title__orders'>Completed orders</h2>";
    require_once "./assets/get_completed_orders.php";
  }
?>

  <script src="order_page_functions.js"></script>
  <script src="./SortTables.js"></script>
  <?php
echo '</main>';
require_once "assets/foot.php"
?>