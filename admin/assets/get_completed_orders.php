<?php
  $sql = "SELECT 
            ws_completed_orders.id         AS OrderNumber,
            ws_completed_orders.order_date AS OrderDate,
            ws_completed_orders.total_cost AS OrderCost,
            ws_customers.first_name     AS CustomerFirstName,
            ws_customers.last_name      AS CustomerLastName,
            ws_delivery_address.city    AS DeliveryCity,
            ws_order_status.status      AS OrderStatus,
            ws_order_status.id          AS OrderStatusId
          FROM 
            ws_completed_orders
          LEFT JOIN
            ws_customers
          ON
            ws_completed_orders.customer_id = ws_customers.id
          LEFT JOIN
            ws_delivery_address
          ON
            ws_completed_orders.delivery_address_id = ws_delivery_address.id
          LEFT JOIN
            ws_order_status
          ON
            ws_completed_orders.status_id = ws_order_status.id
            ";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $completedOrdersResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $completedOrdersGrouped = [];
  // echo "<pre>";
  // print_r($completedOrdersResults);
  // echo "</pre>";

  foreach($completedOrdersResults as $key => $row) {
    // The order id for this row
    $currentOrderId = $row['OrderNumber'];
    $orderType = "completed";

    $completedOrdersGrouped[$key] = [
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

if (empty($completedOrdersResults)) {
    echo "<h2>No completed orders</h2>";
} else {
    echo "<h2>Completed orders</h2>";
  }
echo "<h2>Filter orders</h2>
      <label for='completedTextFilter'>Filter by city</label>
      <input type='text' id='completedTextFilter' oninput='filterOrders(completedOrdersFromPHP)'>
      <table id='completedtable'>
      <thead>
      <tr>
      <th>Order number</th>
      <th>Customer</th>
      <th>City</th>
      <th onclick='sortTableDate(3)'>Order date</th>
      <th onclick='sortTable(4)'>Total Amount</th>
      <th onclick='sortTableStatus(5)'>Status</th>
      <th> </th>
      </tr>
      </thead>
      <tbody id='completedOrdersTable'>";

$rows = "";
foreach($completedOrdersGrouped as $key => $order):
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
          <td>$totalSum SEK</td>
          <td>$orderStatus</td>
          <td>
					  <form action='' method='POST'>
					    <button type='submit'><i class='far fa-eye'></i></button>
					    <input type='hidden' name='p_id' value='$id'>
					  </form>
					</td>
        </tr>";
endforeach;
echo $rows;
echo '</tbody></table>';
?>

<script>
  let completedOrdersFromPHP = <?php echo json_encode($completedOrdersGrouped); ?> ;
</script>