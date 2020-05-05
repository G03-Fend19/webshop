<?php
require_once "../db.php";

$sql = "SELECT (SELECT COUNT(ws_products.id ) FROM ws_products WHERE ws_products.active = 1) AS NumberOfProducts,
              (SELECT COUNT(ws_categories.id) FROM ws_categories) AS NumberOfCategories,
              (SELECT COUNT(ws_active_orders.id) FROM ws_active_orders) AS NumberOfActiveOrders";

$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $NumberOfProducts = htmlspecialchars($row['NumberOfProducts']);
    $NumberOfCategories = htmlspecialchars($row['NumberOfCategories']);
    $NumberOfActiveOrders = htmlspecialchars($row['NumberOfActiveOrders']);
}

?>

<main class="main__admin__dashboard admin__tables">

  <div class="main__admin__text">
    <h1 class="headline__php">Dashboard</h1>
  </div>
  <a href="products_page.php" class="dashboard__box">
    <h2 class="tracking-in-expand"><?php echo $NumberOfProducts; ?></h2>
    <h3 class="tracking-in-expand">Products</h3>
  </a>

  <a href="category-table.php" class="dashboard__box">
    <h2 class="tracking-in-expand"><?php echo $NumberOfCategories; ?></h2>
    <h3 class="tracking-in-expand">Categories</h3>
  </a>

  <a href="orders_page.php?orders=active" class="dashboard__box">
    <h2 class="tracking-in-expand"><?php echo $NumberOfActiveOrders; ?></h2>
    <h3 class="tracking-in-expand">Active orders</h3>
  </a>

  <?php
  $sql = "SELECT 
            ws_active_orders.id         AS OrderNumber,
            ws_active_orders.order_date AS OrderDate,
            ws_active_orders.total_cost AS OrderCost,
            ws_customers.first_name     AS CustomerFirstName,
            ws_customers.last_name      AS CustomerLastName,
            ws_delivery_address.city    AS DeliveryCity,
            ws_order_status.status      AS OrderStatus,
            ws_order_status.id          AS OrderStatusId
          FROM 
            ws_active_orders
          LEFT JOIN
            ws_customers
          ON
            ws_active_orders.customer_id = ws_customers.id
          LEFT JOIN
            ws_delivery_address
          ON
            ws_active_orders.delivery_address_id = ws_delivery_address.id
          LEFT JOIN
            ws_order_status
          ON
            ws_active_orders.status_id = ws_order_status.id
            ORDER BY OrderDate DESC
            LIMIT 5";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $activeOrdersResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $activeOrdersGrouped = [];
  // echo "<pre>";
  // print_r($activeOrdersResults);
  // echo "</pre>";

  foreach($activeOrdersResults as $key => $row) {
    // The order id for this row
    $orderType = "active";

    $activeOrdersGrouped[$key] = [
      "OrderType" => $orderType,
      "OrderNumber" => $row['OrderNumber'],
      "OrderDate" => $row['OrderDate'],
      "OrderCost" => $row["OrderCost"],
      "CustomerFirstName" => $row["CustomerFirstName"],
      "CustomerLastName" => $row["CustomerLastName"],
      "DeliveryCity" => $row["DeliveryCity"],
      "OrderStatus" => $row["OrderStatus"],
      "OrderStatusId" => $row["OrderStatusId"],
    ];
  }

echo "<section class='active-orders'>";
if (empty($activeOrdersResults)) {
    echo "<h2>No active orders</h2>";
} else {
    // echo "<h2 class='headline__php'>Active orders</h2>";
}
echo "<h4>New orders</h4>
      <table id='activetable'>
        <thead>
          <tr>
            <th>Order number</th>
            <th>Customer</th>
            <th>City</th>
            <th>Order date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th> </th>
          </tr>
        </thead>
      <tbody id='activeOrdersTable'>";
$rows = "";
foreach($activeOrdersGrouped as $key => $order):
  $orderNumber = htmlspecialchars($order['OrderNumber']);
  $customerFirstName = htmlspecialchars($order['CustomerFirstName']);
  $customerLastName = htmlspecialchars($order['CustomerLastName']);
  $fullName = $customerFirstName . " " . $customerLastName;
  $city = htmlspecialchars($order['DeliveryCity']);
  $orderDate = htmlspecialchars($order['OrderDate']);
  $totalSum = htmlspecialchars($order['OrderCost']);
  $orderStatus = htmlspecialchars($order['OrderStatus']);
  $orderStatusId = htmlspecialchars($order['OrderStatusId']);

  $rows.= "<tr>
          <td>#$orderNumber</td>
          <td>$fullName</td>
          <td>$city</td>
          <td>$orderDate</td>
          <td>$totalSum</td>
          <td>
              $orderStatus
          </td>
          <td>
					  <form action='' method='POST'>
					    <button type='submit'><i class='far fa-eye'></i></button>
					    <input type='hidden' name='o_id' value='$orderNumber'>
					  </form>
					</td>
        </tr>";
endforeach;
echo $rows;
echo '</tbody></table></section>';
?>

</main>

