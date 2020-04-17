<?php
  header("Content-Type: application/json; charset=UTF-8");
  
  require_once "../db.php";

  $productsSql = "SELECT
                  ws_products.name        AS ProductName,
                  ws_products.description AS ProductDescription,
                  ws_products.price       AS ProductPrice,
                  ws_products.id          AS ProductId,
                  ws_products.stock_qty   AS ProductQty,
                  ws_images.img           AS ImageName,
                  ws_images.id            AS ImageId,
                  ws_categories.name      AS CategoryName,
                  ws_categories.id        AS CategoryId
                FROM
                  ws_products,
                  ws_images,
                  ws_products_images,
                  ws_categories,
                  ws_products_categories
                WHERE
                  ws_products.id = ws_products_images.product_id 
                AND
                  ws_images.id = ws_products_images.img_id 
                AND
                  ws_products.id = ws_products_categories.product_id 
                AND
                  ws_categories.id = ws_products_categories.category_id 
                GROUP BY ws_products.id
                  ";

  $productsStmt = $db->prepare($productsSql);
  $productsStmt->execute();

  $categoriesSql = "SELECT * FROM ws_categories";

  $categoriesStmt = $db->prepare($categoriesSql);
  $categoriesStmt->execute();


  $categories = [];
  $products = [];

  while($row= $productsStmt->fetch(PDO::FETCH_ASSOC)) :
    $productId= htmlspecialchars($row['ProductId']);
    $productName= htmlspecialchars_decode($row['ProductName']);
    $description= htmlspecialchars($row['ProductDescription']);
    $stock_qty= htmlspecialchars($row['ProductQty']);
    $price= htmlspecialchars($row['ProductPrice']);
    $category = htmlspecialchars($row['CategoryName']);
    $image= htmlspecialchars($row['ImageName']);

    $product = array("id" => $productId,
                     "name" => $productName,
                     "category" => $category,
                     "description" => $description,
                     "stockQty" => $stock_qty,
                     "price" => $price
                    );

    $products[] = $product;
  endwhile;
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
    }
  }
?>