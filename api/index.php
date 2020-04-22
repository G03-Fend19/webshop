<?php
  header("Content-Type: application/json; charset=UTF-8");
  
  require_once "../db.php";

  $productsSql = "SELECT 
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
                    ";

  $productsStmt = $db->prepare($productsSql);
  $productsStmt->execute();

  $productsResults = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
  // echo $sql;
  // echo "---<br />";
  // print_r($productsResults);


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
  $products = [];

  foreach($productsResults as $row) {
    // The product id for this row
    $currentProductId = $row["ProductId"];

    // If we've already added this product
    if(in_array($currentProductId, $products)) {

      // Just add the additional image name to the imgIds array
      $products[$currentProductId]["imgNames"][] = $row["ImageName"];
    } else {

      // If we haven't added the product yet
        $products[$currentProductId] = [
          "id" => intval($currentProductId),
          "name" => $row["ProductName"],
          "description" => $row["ProductDescription"],
          "price" => intval($row["ProductPrice"]),
          "qty" => intval($row["ProductQty"]),
          "category" => $row["CategoryName"],
          "images" => [], // Start with empty
        ];
      
      // If there is an image for this row, add it
      if($row["ProductImageImageId"]) {
        $products[$currentProductId]["images"][] = "http://matildasoderblom.se/webshop/media/product_images/" . $row["ImageName"];
      }
      
    }
  }

  $categoriesSql = "SELECT * FROM ws_categories";

  $categoriesStmt = $db->prepare($categoriesSql);
  $categoriesStmt->execute();


  $categories = [];

  while($row= $categoriesStmt->fetch(PDO::FETCH_ASSOC)) :
    $categoryId= htmlspecialchars($row['id']);
    $categoryName= htmlspecialchars_decode($row['name']);

    $category = array("id" => $categoryId,
                     "name" => $categoryName
                     );

    $categories[] = $category;
  endwhile;

  $allData = array("categories" => $categories,
                   "products" => $products);

  $noData = array("error" => "Endpoint not found");

  $productJson = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  $categoriesJson = json_encode($categories, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

  if(isset($_GET['data'])) {
    if ($_GET['data'] == "products") {
      $productJson = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      echo $productJson;
    } elseif ($_GET['data'] == "categories") {
      $categoriesJson = json_encode($categories, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      echo $categoriesJson;
    } elseif ($_GET['data'] == "all") {
      $allDataJson = json_encode($allData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      echo $allDataJson;
    } else {
      $error = json_encode($noData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      echo $error;
    }
  } else {
    $allDataJson = json_encode($allData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      echo $allDataJson;
  }
?>