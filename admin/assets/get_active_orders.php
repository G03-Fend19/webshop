<?php
  $sql = "SELECT 
            ws_active_orders.id         AS OrderNumber,
            ws_active_orders.order_date AS OrderDate,
            ws_active_orders.total_cost AS OrderCost,
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
          WHERE
            ws_orders_products.product_qty >= 0
          ";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $activeOrdersResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $activeOrdersGrouped = [];
  // echo "<pre>";
  // print_r($activeOrdersResults);
  // echo "</pre>";
  foreach($activeOrdersResults as $currentOrderNumber => $row) {
    // The order id for this row
    $orderType = "active";
    $currentOrderNumber = $row['OrderNumber'];
    if(isset($activeOrdersGrouped[$currentOrderNumber])) {
      $activeOrdersGrouped[$currentOrderNumber]["Products"][] =  [
        "ProductName" => htmlspecialchars($row["ProductName"]),
        "ProductPrice" => htmlspecialchars($row["ProductPrice"]),
        "ProductDesc" => htmlspecialchars($row["ProductDesc"]),
        "ProductId" => htmlspecialchars($row["ProductId"]),
        "ProductQty" => htmlspecialchars($row["OrderProductQty"]),
        "ProductDate" => htmlspecialchars($row["ProductDate"]),
        "Stock" => htmlspecialchars($row["Stock"]),
      ];
    } else {
      $activeOrdersGrouped[$currentOrderNumber] = [
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
        $activeOrdersGrouped[$currentOrderNumber]["Products"][] =  [
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

  // echo "<pre>";
  // print_r($activeOrdersGrouped);
  // echo "</pre>";
  // echo "Halloj";

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
      <table id='activetable' class='ordertable'>
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
  $street = htmlspecialchars($order['DeliveryStreet']);
  $postal = htmlspecialchars($order['DeliveryPostal']);
  $orderDate = htmlspecialchars($order['OrderDate']);
  $totalSum = htmlspecialchars($order['OrderCost']);
  $orderStatus = htmlspecialchars($order['OrderStatus']);
  $orderStatusId = htmlspecialchars($order['OrderStatusId']);
  $productsArr = $order['Products'];
  $returnUrl = $_SERVER['REQUEST_URI'];
  $productsTr = "";
  foreach ($productsArr as $key => $product) {
    $productName = htmlspecialchars($product['ProductName']);
    if (strlen($productName) > 20) {
      $productName = substr($productName, 0, 20) . "...";
  }
    $productPrice = htmlspecialchars($product['ProductPrice']);
    $productDesc = htmlspecialchars($product['ProductDesc']);
    $stock = htmlspecialchars($product['Stock']);
    $ProductDate = htmlspecialchars($product['ProductDate']);
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
                    <td>$sale</td>
                  </tr>
                    ";
    
  }
  

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
           <input type='hidden' name='returnUrl' value='$returnUrl'>
           </form>
          </td>
          <td>
            <button id='openModal' class='open-modal'><i class='far fa-eye'></i></button>

            <div id='activeOrdersModal' data-id='$id' class='order_overview'>
            <div class='order_overview__content'>
              <div class='order_overview__content__header'>
                <span class='close'>&times;</span>
                <h2>Order overview</h2> 
              </div>
              <div class='order_overview__content__body'>
              <p>#$orderNumber</p>
              <p>$fullName</p>
              <p>$street</p>
              <p>$postal</p>
              <p>$city</>
              
            
              
              <table class='overview-table'>
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
              <div class='order_overview__content__footer'>
              <button id='cancel' class='cancel-btn'>Close</button>  
             
              </div>
            </div>
          
          </div>
					</td>
        </>";
endforeach;
echo $rows;
echo '</tbody></table></section>';
?>
<script>
let activeOrdersFromPHP = <?php echo json_encode($activeOrdersGrouped);?> ;
</script>
 <script> 


  const activeOrdersModal = document.getElementById("activeOrdersModal");
  //const span = document.getElementsByClassName("close")[0];
  const cancelBtn = document.getElementById("cancel");

  document.querySelectorAll('.open-modal').forEach(item => {
  item.addEventListener('click', event => {
    let currentModal = event.currentTarget.nextElementSibling;

    currentModal.style.display = "block";

    window.onclick = function(event) {
          if (event.target == currentModal) {
            currentModal.style.display = "none";
          }
        };

    document.addEventListener("click", e => {
          if (e.target.className == "close") {
            currentModal.style.display = "none";
          }
        });
    
        document.addEventListener("click", e => {
          if (e.target.className == "cancel-btn") {
            currentModal.style.display = "none";
          }
        });
  });
});

  </script>