<?php
require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";
?>
<main class="admin__products">
  <div class="admin__products__text">
    <h1>Orders</h1>
  </div>
  <h2>Filter orders</h2>
  <label for="filter-by-status">Filter by status</label>
  <select name="filter-by-status" id="filter-by-status" onchange='filterOrders(ordersFromPHP)'>
    <option value="all" selected>All</option>
    <option value='1'>Pending</option>
    <option value='2'>In progress</option>
  </select>
  <label for="filter-by-text">Filter by city</label>
  <input type="text" id="filter-by-text" oninput='filterOrders(ordersFromPHP)'>
<?php
require_once "./assets/get_active_orders.php";
?>
<script>
  let ordersFromPHP = <?php echo json_encode($results); ?> ;
  // console.log(ordersFromPHP)
</script>

<script src="filter_orders.js"></script>
<?php
echo '</main>';
require_once "assets/foot.php"
?>