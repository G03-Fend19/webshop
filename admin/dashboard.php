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
              ws_products.id              AS ProductId

            
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
              ws_orders_products.product_qty > 0
            ORDER BY OrderDate DESC";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $activeOrdersResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*   echo '<pre>';
  print_r($activeOrdersResults);
  echo '</pre>'; */
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
        "ProductName" => $row["ProductName"],
        "ProductPrice" => $row["ProductPrice"],
        "ProductDesc" => $row["ProductDesc"],
        "ProductId" => $row["ProductId"],
        "ProductQty" => $row["OrderProductQty"],
      ];
    } else {
      $activeOrdersGrouped[$currentOrderNumber] = [
        "Products" => [],
        "OrderType" => $orderType,
        "OrderNumber" => $row['OrderNumber'],
        "OrderDate" => $row['OrderDate'],
        "OrderCost" => $row["OrderCost"],
        "CustomerFirstName" => $row["CustomerFirstName"],
        "CustomerLastName" => $row["CustomerLastName"],
        "DeliveryCity" => $row["DeliveryCity"],
        "DeliveryStreet" => $row["DeliveryStreet"],
        "DeliveryPostal" => $row["DeliveryPostal"],
        "OrderStatus" => $row["OrderStatus"],
        "OrderStatusId" => $row["OrderStatusId"],
      ];
      if ($row['OrderProductId']) {
        $activeOrdersGrouped[$currentOrderNumber]["Products"][] =  [
          "ProductName" => $row["ProductName"],
          "ProductPrice" => $row["ProductPrice"],
          "ProductDesc" => $row["ProductDesc"],
          "ProductId" => $row["ProductId"],
          "ProductQty" => $row["OrderProductQty"],
        ];
      }
    }
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
$counter = 1;
foreach($activeOrdersGrouped as $key => $order):

  if ($counter > 5) {
  break;    
}

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
  $returnUrl = $_SERVER['REQUEST_URI'];


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

    $productsTr .= "<tr>
                    <td>$productName</td>
                    <td>$productDesc</td>
                    <td>$productPrice</td>
                    <td>$productQty</td>
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
        $counter++;
endforeach;
echo $rows;
echo '</tbody></table></section>';
?>

</main>
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

