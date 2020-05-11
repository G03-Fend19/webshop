<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once "./db.php";

  $cartJSON = $_POST['cart'];
  $decodedCartJSON = json_decode($cartJSON, true);
  if ($decodedCartJSON == null) {
    header("Location: ./checkout_page.php?error=empty");
  }
  
  foreach ($decodedCartJSON as $key => $product) {
    $productId = $product['id'];
    $getCurrentStockSQL = "SELECT ws_products.stock_qty AS CurrentProductQty,
                                  ws_products.name AS ProductName
                            FROM ws_products
                            WHERE ws_products.id = :product_id";
      $getCurrentStockStmt = $db->prepare($getCurrentStockSQL);
      $getCurrentStockStmt->bindParam(":product_id", $productId);
      $getCurrentStockStmt->execute();

      $currentStockResults = $getCurrentStockStmt->fetch(PDO::FETCH_ASSOC);
      $currentStock = $currentStockResults['CurrentProductQty'];
      $currentProduct = $currentStockResults['ProductName'];

      if($currentStock <= 0) {
        header("Location: ./checkout_page.php?error=out_of_stock&product=$currentProduct");
      }
  }
  
  
  $cart = $_POST['cart'];
  $firstName = $_POST['firstname'];
  $lastName = $_POST['lastname'];
  $email = $_POST['email'];
  $telNr = $_POST['mobile'];
  
  $street = $_POST['street'];
  $postalCode = $_POST['postal'];
  $city = $_POST['city'];
  
  $customerId;
  $addressId;
  
  //Try to fetch data from database where email from form matches
    $getCustomerSQL = "SELECT
                        ws_customers.id         AS CustomerId,
                        ws_customers.first_name AS FirstName,
                        ws_customers.last_name  AS LastName,
                        ws_customers.email      AS Email,
                        ws_customers.tel_nr     AS Phone
                      FROM 
                        ws_customers
                      WHERE
                        ws_customers.email = :email";
    
    $getCustomerStmt = $db->prepare($getCustomerSQL);
    $getCustomerStmt->bindParam(':email', $email);
    $getCustomerStmt->execute();
  
    $customerResults = $getCustomerStmt->fetch(PDO::FETCH_ASSOC);
  
    //If there was no match in database
    if(empty($customerResults)) {
      //Create a new customer in database
      $createCustomerSQL = "INSERT INTO ws_customers (first_name,last_name, email, tel_nr)
                            VALUES (:first_name, :last_name, :email, :tel_nr)";
      $createCustomerStmt = $db->prepare($createCustomerSQL);
      $createCustomerStmt->bindParam(":first_name", $firstName);
      $createCustomerStmt->bindParam(":last_name", $lastName);
      $createCustomerStmt->bindParam(":email", $email);
      $createCustomerStmt->bindParam(":tel_nr", $telNr);
      $createCustomerStmt->execute();
  
      //and get the new customer from database to get access to auto incremented customer id
      $getNewCustomerSQL = $getCustomerSQL;
      $getNewCustomerStmt = $db->prepare($getNewCustomerSQL);
      $getNewCustomerStmt->bindParam(':email', $email);
      $getNewCustomerStmt->execute();
  
      $newCustomerResults = $getNewCustomerStmt->fetch(PDO::FETCH_ASSOC);
      //Set customer id
      $customerId = $newCustomerResults['CustomerId'];
      // echo $customerId;
      // echo "<br>";
      // echo "New customer $customerId now exists and can be billed";
      // echo "<br>";
    }
    //If there was a match on email and all other data matches
    elseif ($customerResults['FirstName'] == $firstName &&
            $customerResults['LastName'] == $lastName &&
            $customerResults['Email'] == $email &&
            $customerResults['Phone'] == $telNr) {
      //Set customer Id
      $customerId = $customerResults['CustomerId'];
      // echo $customerId;
      // echo "<br>";
      // echo "Customer $customerId exists and can be billed";
      // echo "<br>";
    } else {
      // If email matched but not the rest, display error
      header("Location: ./checkout_page.php?error=mail");
    }

    if(isset($customerId)) {
      //Try to fetch address from database where address inputs from form matches
     $getAddressSQL = "SELECT
        ws_delivery_address.id           AS AddressId,
        ws_delivery_address.street       AS Street,
        ws_delivery_address.postal_code  AS PostalCode,
        ws_delivery_address.city         AS City
      FROM 
        ws_delivery_address
      WHERE
        ws_delivery_address.street = :street
      AND
        ws_delivery_address.postal_code = :postal_code
      AND
        ws_delivery_address.city = :city";
  
      $getAddressStmt = $db->prepare($getAddressSQL);
      $getAddressStmt->bindParam(':street', $street);
      $getAddressStmt->bindParam(':postal_code', $postalCode);
      $getAddressStmt->bindParam(':city', $city);
      $getAddressStmt->execute();
  
      $addressResults = $getAddressStmt->fetch(PDO::FETCH_ASSOC);
  
      if(empty($addressResults)) {
      //Create a new customer in database
      $createAddressSQL = "INSERT INTO ws_delivery_address (street, postal_code, city)
            VALUES (:street, :postal_code, :city)";
      $createAddressStmt = $db->prepare($createAddressSQL);
      $createAddressStmt->bindParam(":street", $street);
      $createAddressStmt->bindParam(":postal_code", $postalCode);
      $createAddressStmt->bindParam(":city", $city);
      $createAddressStmt->execute();
  
      //and get the new customer from database to get access to auto incremented customer id
      $getNewAddressSQL = $getAddressSQL;
      $getNewAddressStmt = $db->prepare($getNewAddressSQL);
      $getNewAddressStmt->bindParam(":street", $street);
      $getNewAddressStmt->bindParam(":postal_code", $postalCode);
      $getNewAddressStmt->bindParam(":city", $city);
      $getNewAddressStmt->execute();
  
      $newAddressResults = $getNewAddressStmt->fetch(PDO::FETCH_ASSOC);
      //Set address id
      $addressId = $newAddressResults['AddressId'];
      // echo $addressId;
      // echo "<br>";
      // echo "New address $addressId now exists and can be billed";
      // echo "<br>";
      } else {
      $addressId = $addressResults['AddressId'];
      // echo "$addressId exists";
      // echo "<br>";
      // echo $addressResults['Street'];
      // echo "<br>";
      // echo $addressResults['PostalCode'];
      // echo "<br>";
      // echo $addressResults['City'];
      }

   }
      
  
    if (isset($customerId) && isset($addressId)) {


      $totalJSON = $_POST['total'];
      $totalSum = json_decode($totalJSON, true);

      if (strtolower($_POST['city']) == "stockholm" || $totalSum > 499) {
        $shippingId = 2;
      } else {
        $shippingId = 1;
      }
  
      $createOrderSQL = "INSERT INTO ws_active_orders (customer_id, delivery_address_id, total_cost, shipping_id)
                          VALUES (:customer_id, :delivery_address_id, :total_cost, :shipping_id)";
      $createOrderStmt = $db->prepare($createOrderSQL);
      $createOrderStmt->bindParam(":customer_id", $customerId);
      $createOrderStmt->bindParam(":delivery_address_id", $addressId);
      $createOrderStmt->bindParam(":total_cost", $totalSum);
      $createOrderStmt->bindParam(":shipping_id", $shippingId);
      $createOrderStmt->execute();

      $getOrderSQL = "SELECT
                        ws_active_orders.id AS OrderId
                      FROM
                        ws_active_orders
                      WHERE
                        ws_active_orders.customer_id = :customer_id 
                      ORDER BY ID DESC
                      LIMIT 1";
      $getOrderStmt = $db->prepare($getOrderSQL);
      $getOrderStmt->bindParam(":customer_id", $customerId);
      $getOrderStmt->execute();

      $orderResults = $getOrderStmt->fetch(PDO::FETCH_ASSOC);
      $orderId = $orderResults['OrderId'];

      // echo "Ny order tillagd";

  
      $cartJSON = $_POST['cart'];
      $decodedCartJSON = json_decode($cartJSON, true);
      
      foreach ($decodedCartJSON as $key => $product) {
        $productId = $product['id'];
        $qty = $product['quantity'];
  
        $addProductSQL = "INSERT INTO ws_orders_products (order_id, product_id, product_qty)
                          VALUES (:order_id, :product_id, :product_qty)";
        $addProductStmt = $db->prepare($addProductSQL);
        $addProductStmt->bindParam(":order_id", $orderId);
        $addProductStmt->bindParam(":product_id", $productId);
        $addProductStmt->bindParam(":product_qty", $qty);
        $addProductStmt->execute();

        $getCurrentStockSQL = "SELECT ws_products.stock_qty
                                AS CurrentProductQty
                                FROM ws_products
                                WHERE ws_products.id = :product_id";
        $getCurrentStockStmt = $db->prepare($getCurrentStockSQL);
        $getCurrentStockStmt->bindParam(":product_id", $productId);
        $getCurrentStockStmt->execute();

        $currentStockResults = $getCurrentStockStmt->fetch(PDO::FETCH_ASSOC);
        $currentStock = $currentStockResults['CurrentProductQty'];
        $newStock = $currentStock - $qty;

        $updateQtySQL = "UPDATE ws_products
                        SET stock_qty = :new_stock
                        WHERE ws_products.id = :product_id";
        $updateQtyStmt = $db->prepare($updateQtySQL);
        $updateQtyStmt->bindParam(":new_stock", $newStock);
        $updateQtyStmt->bindParam(":product_id", $productId);
        $updateQtyStmt->execute();
      }
    }
} else {
  header("Location: ./index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://kit.fontawesome.com/10d18f6c7b.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link href="./media/images/logo.png" rel="icon" type="image/x-icon"/>
  <title>Clothera - Confirm page</title>
</head>

<body>
<header class="header">
    <nav class="fixed">
      <div class="header__logo"><a href="index.php"> <img src="./media/images/logo_white.png" width="40" height="40" />
        </a>
      </div>

      <ul class="header__nav">
        <div class="header__nav__container">
          <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home"
              href="index.php">Home</a>
          </li>

          <li class="header__nav__item"><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
              href="contact.php">Contact</a></li>
        </div>

        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__cart"><i
              class="fas fa-shopping-cart"></i>
            <span class="cart_qty_show"></span>
          </a></li>
      </ul>

      <div class="header__burger">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
    <section class="header__hero">
      <form class="searchform" name="search_form" action="search.php#main" onsubmit="return validateSearchForm()"
        method="GET">
        <input class="searchform__searchbar " placeholder="Search..." type="text" name="search">
        <button class="searchform__searchBtn search "> <i class="fa fa-search"></i></button>
      </form>

      <ul class="toggle_menu">
        <div class="toggle_menu__container">
          <li class="toggle_menu__item"><a class="toggle_menu__item__a toggle_menu__item__home"
              href="index.php">Home</a>
          </li>
          <li class="toggle_menu__item"><a id="contact-desktop" class="toggle_menu__item__a toggle_menu__item__contact2"
              href="contact.php">Contact</a></li>
        </div>

      </ul>
      <section class="cart hidden">
        <div class="cart__menu"></div>
        <section class="cart__product-wrapper"></section>
        <div class="cart__total-checkout"></div>

      </section>
      <div class="header__hero__text">
        <h1>Thank you!</h1>
        <p class="desc__hero__text">Make space in your wardrobe...</p>
      </div>

      <div id="myModal" class="modal">
        <div class="modal__content">
          <div class="modal__content__header">
            <span class="close">&times;</span>
            <h2>Confirmation</h2>
          </div>
          <div class="modal__content__body">
            <p>Are you sure you would like to remove all items from the shopping cart?</p>
          </div>
          <div class="modal__content__footer">
            <button class="cancel-btn">Cancel</button> <button class="clear-cart">
              Clear Cart
            </button>
          </div>
        </div>

      </div>

      <div id="noMoreInStockModal" class="modal">
        <div class="modal__content">
          <div class="modal__content__header">
            <span class="cancel-btn  close">&times;</span>
            <h2>Alert</h2>
          </div>
          <div class="modal__content__body">
            <p>No more in stock</p>
          </div>
          <div class="modal__content__footer">
            <button class="cancel-btn">Cancel</button>
          </div>
        </div>
      </div>

    </section>

  </header>

  <main class="confirmpage">
    <div class="confirmpage__container">
      <section class="confirmpage__order">
        <h1>Thank you for your order!</h1>

        <table class="confirmtable">
          <thead class="confirmtable__thead">
            <tr>
              <th class="confirmtable__thead__productname">Product name</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody class="confirmtable__tbody">
            <!-- Här ska kod från localStorage in med JS -->
          </tbody>
        </table>

        <section class="confirmpage__price">
          <div class="confirmpage__price__products">
            <strong>Price</strong>
            <p class="confirmpage__productprice"></p>
          </div>
          <div class="confirmpage__price__shipping">
            <strong>Shipping fee</strong>
            <p class="confirmpage__shippingfee"></p>
          </div>
          <div class="confirmpage__price__total">
            <strong>Total price</strong>
            <p class="confirmpage__totalprice"></p>
          </div>
        </section>
      </section>

      <section class="confirmpage__information">
        <h2>It will be shipped to...</h2>

        <div class="confirmpage__shipping">
          <div class="confirmpage__shipping__customer">
            <h3>Customer</h3>
          </div>

          <div class="confirmpage__shipping__payment">
            <h3>Payment method</h3>
          </div>
        </div>
      </section>
      <a class="confirmpage__shopmorebtn" href="index.php"><button>Shop more</button></a>

    </div>
  </main>
  <script src="confirm_page.js"></script>
  <script>
  localStorage.clear()
  </script>

  <?php


require_once './assets/foot.php';