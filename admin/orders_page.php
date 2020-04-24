<?php
require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";
?>
<main class="admin__products">
  <div class="admin__products__text">
    <h1>Orders</h1>
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
    require_once "./assets/get_active_orders.php";
    require_once "./assets/get_completed_orders.php";
  }
?>

<script src="filter_orders.js"></script>
<?php
echo '</main>';
require_once "assets/foot.php"
?>