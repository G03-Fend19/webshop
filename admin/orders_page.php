<?php
require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";

?>
<main class="admin__tables">
  <div class="admin__tables__text">
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

<script src="order_page_functions.js"></script>
<script src="./SortTables.js"></script>
<?php
echo '</main>';
require_once "assets/foot.php"
?>