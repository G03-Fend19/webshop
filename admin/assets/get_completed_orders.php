<?php
$sql = "SELECT 
ws_completed_orders.id         AS OrderNumber,
ws_completed_orders.order_date AS OrderDate,
ws_completed_orders.total_cost AS OrderCost,
ws_customers.first_name     AS CustomerFirstName,
ws_customers.last_name      AS CustomerLastName,
ws_delivery_address.street   AS DeliveryStreet,
ws_delivery_address.postal_code    AS DeliveryPostal,
ws_delivery_address.city    AS DeliveryCity,
ws_order_status.status      AS OrderStatus,
ws_order_status.id          AS OrderStatusId,
ws_orders_products.product_qty AS OrderProductQty,
ws_orders_products.product_id AS OrderProductId,
ws_products.name            AS ProductName,
ws_products.price           AS ProductPrice,
ws_products.description      AS ProductDesc,
ws_products.id              AS ProductId,
ws_products.stock_qty       AS Stock, 
ws_products.added_date      AS ProductDate


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
WHERE
ws_orders_products.product_qty > 0
";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $completedOrdersResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $completedOrdersGrouped = [];
  // echo "<pre>";
  // print_r($completedOrdersResults);
  // echo "</pre>";

  foreach($completedOrdersResults as $currentOrderNumber => $row) {
    // The order id for this row
    $orderType = "completed";
    $currentOrderNumber = htmlspecialchars($row['OrderNumber']);
    if(isset($completedOrdersGrouped[$currentOrderNumber])) {
      $completedOrdersGrouped[$currentOrderNumber]["Products"][] =  [
        "ProductName" => htmlspecialchars($row["ProductName"]),
        "ProductPrice" => htmlspecialchars($row["ProductPrice"]),
        "ProductDesc" => htmlspecialchars($row["ProductDesc"]),
        "ProductId" => htmlspecialchars($row["ProductId"]),
        "ProductQty" => htmlspecialchars($row["OrderProductQty"]),
        "ProductDate" => htmlspecialchars($row["ProductDate"]),
        "Stock" => htmlspecialchars($row["Stock"]),
        
      ];
    } else {
      $completedOrdersGrouped[$currentOrderNumber] = [
        "Products" => [],
        "OrderType" => htmlspecialchars($orderType),
        "OrderNumber" => htmlspecialchars($row['OrderNumber']),
        "OrderDate" => htmlspecialchars($row['OrderDate']),
        "OrderCost" => htmlspecialchars($row["OrderCost"]),
        "CustomerFirstName" => htmlspecialchars($row["CustomerFirstName"]),
        "CustomerLastName" => htmlspecialchars($row["CustomerLastName"]),
        "DeliveryCity" => htmlspecialchars($row["DeliveryCity"]),
        "DeliveryStreet" => htmlspecialchars($row["DeliveryStreet"]),
        "DeliveryPostal" => htmlspecialchars($row["DeliveryPostal"]),
        "OrderStatus" => htmlspecialchars($row["OrderStatus"]),
        "OrderStatusId" => htmlspecialchars($row["OrderStatusId"]),
      ];
      if ($row['OrderProductId']) {
        $completedOrdersGrouped[$currentOrderNumber]["Products"][] =  [
          "ProductName" => htmlspecialchars($row["ProductName"]),
          "ProductPrice" => htmlspecialchars($row["ProductPrice"]),
          "ProductDesc" => htmlspecialchars($row["ProductDesc"]),
          "ProductId" => htmlspecialchars($row["ProductId"]),
          "ProductQty" => htmlspecialchars($row["OrderProductQty"]),
          "ProductDate" => htmlspecialchars($row["ProductDate"]),
          "Stock" => htmlspecialchars($row["Stock"]),
        ];
      }
    }
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
      <table id='completedtable' class='ordertable'>
      <thead>
      <tr>
      <th>Order number</th>
      <th>Customer</th>
      <th>City</th>
      <th onclick='sortTableDate(3)'>Order date <i class='fas fa-sort'></i></th>
      <th onclick='sortTable(4)'>Total Amount <i class='fas fa-sort'></th>
      <th>Status <i class='fas fa-sort'></th>
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
  $street = htmlspecialchars($order['DeliveryStreet']);
  $postal = htmlspecialchars($order['DeliveryPostal']);
  $orderDate = htmlspecialchars($order['OrderDate']);
  $totalSum = htmlspecialchars($order['OrderCost']);
  $orderStatus = htmlspecialchars($order['OrderStatus']);
  $orderStatusId = htmlspecialchars($order['OrderStatusId']);
  $productsArr = $order['Products'];
  $productsTr = "";
  foreach ($productsArr as $key => $product) {
    $productName = htmlspecialchars($product['ProductName']);
    if (strlen($productName) > 20) {
      $productName = substr($productName, 0, 20) . "...";
  }
    $productPrice = htmlspecialchars($product['ProductPrice']);
    $productDesc = htmlspecialchars($product['ProductDesc']);
    if (strlen($productDesc) > 20) {
      $productDesc = substr($productDesc, 0, 20) . "...";
  }
    $productId = htmlspecialchars($product['ProductId']);
    $productQty = htmlspecialchars($product['ProductQty']);
    if(strtotime($ProductDate)<strtotime('-1 year') and $stock<10){
    
      $sale = "yes";
      }else{
      $sale = "no";
      };            
 
    $productsTr .= "<tr>
                    <td>$productName</td>
                    <td>$productDesc</td>
                    <td>$productPrice</td>
                    <td>$productQty</td>
                    <td>$sale<td/>
                  </tr>
                    ";
    
  }

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
              <p>$street</p>
              <p>$postal</p>
              <p>$city</>
              <p>$orderDate</p>
              <table>
                <thead>
                  <tr>
                    <td>Product</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>On Sale</td>
                  </tr>
                </thead>
                <tbody>
                    $productsTr
                </tbody>
              </table>
              <p>Total price:</p>
              <p>$totalSum SEK</p>

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
let completedOrdersFromPHP = <?php echo json_encode($completedOrdersGrouped);?> ;
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