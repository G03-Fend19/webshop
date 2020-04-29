<?php

require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";

$headline= "All products";
if (isset($_GET['category_id'])){
  $id = $_GET['category_id'];
  $sql = "SELECT * FROM ws_categories
  WHERE id = :id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $headline = $row['name'];
  }
}

?>
<main class="admin__products">
  <div class="admin__products__text">
    <h1 class="headline__php"><?php echo $headline?></h1>
    <a href="./create_product.php">
      <button class="admin__tables__text__addProduct">
        <p>Add new</p>
        <i class="fas fa-plus"></i>
      </button>
    </a>
  </div>
  <?php

echo "<script type='text/javascript' src='SortTables.js'></script>";
?>
  <?php
if (isset($_GET['category_id'])) {
    $categoryId = htmlspecialchars($_GET['category_id']);
    $sql = "SELECT
              ws_products.name          AS ProductName,
              ws_products.description   AS ProductDescription,
              ws_products.price         AS ProductPrice,
              ws_products.id            AS ProductId,
              ws_products.stock_qty     AS ProductQty,
              ws_images.img             AS ImageName,
              ws_products_images.img_id AS ProductImageImageId,
              ws_categories.id          AS CategoryId,
              ws_categories.name        AS CategoryName
            FROM
              ws_products
            LEFT JOIN
              ws_products_images
            ON
              ws_products.id = ws_products_images.product_id
            LEFT JOIN
              ws_images
            ON
              ws_products_images.img_id = ws_images.id
            LEFT JOIN
              ws_products_categories
            ON
              ws_products.id = ws_products_categories.product_id
            LEFT JOIN
              ws_categories
            ON
              ws_products_categories.category_id = ws_categories.id
            WHERE
              ws_categories.id = :category_id
            AND
              ws_products_categories.category_id = :category_id
            AND ws_products.active = 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":category_id", $categoryId);
    $stmt->execute();
} else {
    $sql = "SELECT
              ws_products.name        AS ProductName,
              ws_products.description AS ProductDescription,
              ws_products.price       AS ProductPrice,
              ws_products.id          AS ProductId,
              ws_products.stock_qty   AS ProductQty,
              ws_images.img           AS ImageName,
              ws_images.id            AS ImageId,
              ws_products_images.img_id AS ProductImageImageId,
              ws_categories.name      AS CategoryName,
              ws_categories.id        AS CategoryId
              FROM
                ws_products
              LEFT JOIN
                ws_products_images
              ON
                ws_products.id = ws_products_images.product_id
              LEFT JOIN
                ws_images
              ON
                ws_products_images.img_id = ws_images.id
              LEFT JOIN
                ws_products_categories
              ON
                ws_products.id = ws_products_categories.product_id
              LEFT JOIN
                ws_categories
              ON
                ws_products_categories.category_id = ws_categories.id
              WHERE
              ws_products.active = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo $sql;
// echo "---<br />";
// print_r($results);

// [
//   [1] => [ <-- key is ProductId
//     "imgNames" => [
//        [0] => "hund.jpg",
//        [1] => "sfndsjkf.jpg"
//     ],
//     "ProductName" => "Korv",
//     ...
//   ],
//   [24] => [

//   ]
// ]
$grouped = [];

foreach ($results as $row) {
    // The product id for this row
    $currentProductId = $row["ProductId"];

    // If we've already added this product
    if (in_array($currentProductId, $grouped)) {

        // Just add the additional image name to the imgIds array
        $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
    } else {

        // If we haven't added the product yet
        $grouped[$currentProductId] = [
            "imgNames" => [], // Start with empty
            "ProductId" => $currentProductId,
            "ProductName" => $row["ProductName"],
            "ProductDescription" => $row["ProductDescription"],
            "ProductPrice" => $row["ProductPrice"],
            "ProductQty" => $row["ProductQty"],
            "CategoryName" => $row["CategoryName"],
        ];

        // If there is an image for this row, add it
        if ($row["ProductImageImageId"]) {
            $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
        }

    }
}
//   echo "<pre>";
// print_r($grouped);
// echo "</pre>";

// $stmtCheck = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($results)) {
    echo "<h4>No products in this category</h4>";
} else {
    echo "<table id='producttable'>
  <thead>
  <tr>
  <th></th>
  <th>Product number</th>
  <th>Name</th>
  <th>Description</th>
  <th>Category</th>
  <th>Stock qty</th>
  <th onclick='sortTable(6)'>Price</th>
  <th> </th>
  <th> </th>
  </tr>
  </thead>
  <tbody>";
}

foreach ($grouped as $productId => $product):

    $stmtCheck = $product;
    $id = htmlspecialchars($product['ProductId']);
    $name = htmlspecialchars_decode($product['ProductName']);
    $description = htmlspecialchars($product['ProductDescription']);
    $stock_qty = htmlspecialchars($product['ProductQty']);
    $price = htmlspecialchars($product['ProductPrice']);
    $category = htmlspecialchars($product['CategoryName']);
    $descriptionShort = substr($description, 0, 20);
    if (empty($product['imgNames'])) {
        $productImg = "placeholder.jpg";
    } else {
        $productImg = htmlspecialchars($product['imgNames'][0]);
    }
    echo "<tr>
													            <td><img src='../media/product_images/$productImg' alt='placeholder'></td>
													            <td>#$id</td>
													            <td>$name</td>
													            <td>$descriptionShort...</td>
													            <td>$category</td>
													            <td>$stock_qty st</td>
													            <td>$price SEK</td>
										                  <td>
										                    <form action='./edit_product.php' method='POST'>
										                      <button type='submit'><i class='fas fa-pen'></i></button>
										                      <input type='hidden' name='p_id' value='$id'>
										                    </form>
										                  </td>
													            <td>
													                <form action='assets/delete-product.php' onsubmit='return deleteProductConfirm()' method='POST'>
													                  <button type='submit'><i class='far fa-trash-alt'></i></button>
													                  <input type='hidden' name='id' value='$id'>
													               </form>
													            </td>
													         </tr>";
endforeach;
echo '</tbody></table>';
echo '</main>';
?>
  <!-- <script src="active_pages.js"></script> -->
  <script>
  function deleteProductConfirm() {
    if (confirm("Are you sure you want to delete this product?")) {
      return true;
    } else {
      return false;
    }
  }
  </script>

  <?php

require_once 'assets/foot.php';
?>