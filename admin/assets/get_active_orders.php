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
            ";
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


if (empty($activeOrdersResults)) {
    echo "<h2>No active orders</h2>";
} else {
    echo "<h2>Active orders</h2>";
}
echo "<h2>Filter orders</h2>
      <label for='activeStatusFilter'>Filter by status</label>
      <select name='activeStatusFilter' id='activeStatusFilter' onchange='filterOrders(activeOrdersFromPHP)'>
        <option value='all' selected>All</option>
        <option value='1'>Pending</option>
        <option value='2'>In progress</option>
      </select>
      <label for='activeTextFilter'>Filter by city</label>
      <input type='text' id='activeTextFilter' oninput='filterOrders(activeOrdersFromPHP)'>
      <table>
        <thead>
          <tr>
            <th>Order number</th>
            <th>Customer</th>
            <th>City</th>
            <th>Order date</>
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
          <td>$totalSum SEK</td>
          <td>
          <form id='shouldUpdate$orderNumber' action='./assets/update_order_status.php' method='POST'>
           <select name='statusSelect$orderNumber' id='statusSelect$orderNumber' onchange='updateStatus($orderNumber)'>
            <option value='1'";
              $orderStatusId == 1 ? $rows.= ' selected':null; $rows.= ">
              Pending
            </option>
            <option value='2'";
              $orderStatusId == 2 ? $rows.= ' selected':null; $rows.= ">
              In progress
            </option>
            <option value='3'";
              $orderStatusId == 3 ? $rows.= ' selected':null; $rows.= ">
              Completed
            </option>
           </select>
           <input type='hidden' name='o_id' value='$orderNumber'>
           </form>
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
echo '</tbody></table>';
?>

<script>
  let activeOrdersFromPHP = <?php echo json_encode($activeOrdersGrouped); ?> ;
</script>