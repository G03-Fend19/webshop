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

          LEFT JOIN
          
            ws_orders_products
          ON
            ws_completed_orders.id = ws_orders_products.order_id 
          LEFT JOIN    
            ws_products
          ON 
            ws_products.id = ws_orders_products.product_id  
          LEFT JOIN    
            ws_products_images
          ON
            ws_products_images.product_id = ws_products.id
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
      "OrderProductQty" => $row["OrderProductQty"],
      "ProductName" => $row["ProductName"],
      "ProductPrice" => $row["ProductPrice"],
      "ProductDesc" => $row["ProductDesc"],
      "ProductsImgId" => $row["ProductsImgId"],

    ];
  }
echo "<section class='completed-orders'>";
if (empty($completedOrdersResults)) {
    echo "<h2>No completed orders</h2>";
} else {
    // echo "<h2>Completed orders</h2>";
  }
echo "<div class='completed-orders__filter'>
        <h3>Filter orders</h3>
        <input type='text' id='completedTextFilter' oninput='filterOrders(completedOrdersFromPHP)' placeholder='Filter by city'>
      </div>
      <table id='completedtable'>
      <thead>
      <tr>
      <th>Order number</th>
      <th>Customer</th>
      <th>City</th>
      <th onclick='sortTableDate(3)'>Order date <i class='fas fa-sort'></i></th>
      <th onclick='sortTable(4)'>Total Amount <i class='fas fa-sort'></th>
      <th onclick='sortTableStatus(5)'>Status <i class='fas fa-sort'></th>
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
  $orderProductQty = htmlspecialchars($order['OrderProductQty']);
  $productName = htmlspecialchars($order['ProductName']);
  $productPrice = htmlspecialchars($order['ProductPrice']);
  $productDesc = htmlspecialchars($order['ProductDesc']);

  $rows.= "<tr>
          <td id='foo'>#$orderNumber</td>
          <td>$fullName</td>
          <td>$city</td>
          <td>$orderDate</td>
          <td>$totalSum SEK</td>
          <td>$orderStatus</td>     
          <td>
					 
            <button id='openModal' class='open-modal'><i class='far fa-eye'></i></button>

            <div id='myModal' data-id='$id' class='modal'>
            <div class='modal__content'>
              <div class='modal__content__header'>
                <span class='close'>&times;</span>
                <h2>Order overview</h2> 
              </div>
              <div class='modal__content__body'>
              <p>#$orderNumber</p>
              <p>$fullName</p>
              <p>$city</p>
              <p>$orderDate</p>
              <p>$totalSum</p>
              <p>$orderProductQty</p>
              <p>$productName</p>
              <p>$productPrice</p>
              <p>$productDesc</p>
            
              </div>
              <div class='modal__content__footer'>             
              </div>
            </div>
          
          </div>
					</td>
        </tr>";
endforeach;
echo $rows;
echo '</tbody></table></section>';
?>

<script>
let activeOrdersFromPHP = <?php echo json_encode($activeOrdersGrouped);?> ;
</script>
 <script> 
  const modal = document.getElementById("myModal");
  const span = document.getElementsByClassName("close")[0];

  document.querySelectorAll('.open-modal').forEach(item => {
  item.addEventListener('click', event => {
    let currentModal = event.currentTarget.nextElementSibling;
    let currentSpan = event.currentTarget.nextElementSibling;

    currentModal.style.display = "block";
        //close the modal
        currentSpan.onclick = function() {
          currentModal.style.display = "none";
        };
        // clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            currentModal.style.display = "none";
          }
        };
        document.addEventListener("click", e => {
          if (e.target.className == "cancel-btn") {
            currentModal.style.display = "none";
          }
        });
  });
});
  </script>