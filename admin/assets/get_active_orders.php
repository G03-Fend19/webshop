<?php
  $sql = "SELECT 
            ws_active_orders.id         AS OrderNumber,
            ws_active_orders.order_date AS OrderDate,
            ws_active_orders.total_cost AS OrderCost,
            ws_customers.first_name     AS CustomerFirstName,
            ws_customers.last_name      AS CustomerLastName,
            ws_delivery_address.city    AS DeliveryCity,
            ws_order_status.status      AS OrderStatus,
            ws_order_status.id          AS OrderStatusId,
            ws_orders_products.product_qty AS OrderProductQty,
            ws_products.name            AS ProductName,
            ws_products.price           AS ProductPrice,
            ws_products.description      AS ProductDesc,
            ws_products_images.img_id    AS ProductsImgId


           
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
          LEFT JOIN
            ws_orders_products
          ON
            ws_active_orders.id = ws_orders_products.order_id 
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
      "OrderProductQty" => $row["OrderProductQty"],
      "ProductName" => $row["ProductName"],
      "ProductPrice" => $row["ProductPrice"],
      "ProductDesc" => $row["ProductDesc"],
      "ProductsImgId" => $row["ProductsImgId"],

    ];
  }

echo "<section class='active-orders'>";
if (empty($activeOrdersResults)) {
    echo "<h2>No active orders</h2>";
} else {
    // echo "<h2 class='headline__php'>Active orders</h2>";
}
echo "<div class='active-orders__filter'>
        <h3>Filter orders</h3>
        <select name='activeStatusFilter' id='activeStatusFilter' onchange='filterOrders(activeOrdersFromPHP)'>
          <option value='all' selected>All</option>
          <option value='1'>Pending</option>
          <option value='2'>In progress</option>
        </select>
        <input type='text' id='activeTextFilter' oninput='filterOrders(activeOrdersFromPHP)' placeholder='Filter by city'>
      </div>
      <table id='activetable'>
        <thead>
          <tr>
            <th>Order number </th>
            <th>Customer</th>
            <th>City</th>
            <th onclick='sortTableDate(3)'>Order date <i class='fas fa-sort'></th>
            <th onclick='sortTable(4)'>Total Amount <i class='fas fa-sort'></th>
            <th onclick='sortTableStatus(5)'>Status <i class='fas fa-sort'></th>
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
  $orderProductQty = htmlspecialchars($order['OrderProductQty']);
  $productName = htmlspecialchars($order['ProductName']);
  $productPrice = htmlspecialchars($order['ProductPrice']);
  $productDesc = htmlspecialchars($order['ProductDesc']);
  $productDesc = htmlspecialchars($order['ProductsImgId']);
  

  $rows.= "<tr>
          <td>#$orderNumber</td>
          <td>$fullName</td>
          <td>$city</td>
          <td>$orderDate</td>
          <td>$totalSum</td>
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
              <p>$orderProductQty</p>
              <p>$productPrice</p>
              <p>$totalSum</p>
              <p>$productName</p>
              <p>$productDesc</p>
              </div>
              <div class='modal__content__footer'>
              <button id='cancel' class='cancel-btn'>Cancel</button>  
             
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
  const cancelBtn = document.getElementById("cancel");

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