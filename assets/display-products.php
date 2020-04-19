<?php
$id = "";
$categoryName = "";

if (isset($_GET['id']) && $_GET['id'] !== "") {
  require_once "./db.php";
  $id = htmlspecialchars($_GET['id']);

  $sql = "SELECT 
            ws_products.name        AS ProductName, 
            ws_products.description AS ProductDescription,
            ws_products.price       AS ProductPrice,
            ws_products.id          AS ProductId,
            ws_products.stock_qty   AS ProductQty,
            ws_images.img           AS ImageName,
            ws_images.id            AS ImageId,
            ws_categories.id        AS CategoryId,
            ws_categories.name      AS CategoryName
          FROM 
            ws_products,
            ws_images,
            ws_categories,
            ws_products_images,
            ws_products_categories
          WHERE 
            ws_products.id = ws_products_images.product_id 
          AND
            ws_images.id = ws_products_images.img_id
          AND
            ws_products.id = ws_products_categories.product_id
          AND
            ws_categories.id = ws_products_categories.category_id
          AND
            ws_categories.id = :id
          AND
            ws_products_categories.category_id = :id
          GROUP BY ws_products.id";
  
  $stmt = $db->prepare($sql);
  $stmt->bindParam(":id", $id);
  $stmt->execute();
} else {
  $sql = "SELECT 
            ws_products.name      AS ProductName, 
            ws_products.price     AS ProductPrice,
            ws_products.id        AS ProductId,
            ws_products.stock_qty AS ProductQty,
            ws_images.img         AS ImageName,
            ws_images.id          AS ImageId
          FROM 
            ws_products,
            ws_images,
            ws_products_images
          WHERE 
            ws_products.id = ws_products_images.product_id 
          AND
            ws_images.id = ws_products_images.img_id
          GROUP BY ws_products.id";

  $stmt = $db->prepare($sql);
  $stmt->execute();
}

  $productsContainer = "<div class='products'>";
  $productCards = "";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $productName = htmlspecialchars($row['ProductName']);
    $productPrice = htmlspecialchars($row['ProductPrice']);
    $productId = htmlspecialchars($row['ProductId']);
    $productQty = htmlspecialchars($row['ProductQty']);
    $productImg = htmlspecialchars($row['ImageName']);
    if (isset($_GET['id'])) {
      $categoryName = htmlspecialchars($row['CategoryName']);
    } 

    $productCards .= "<article class='product-card'>
                        <a href='product.php?id=$productId' class='product-card__image-link'>
                          <div class='image-wrapper'>";
                        $productQty < 1 ? $productCards.= "<div class='out-of-stock'>
                                                            <span class='out-of-stock__msg'>
                                                            Currently out of stock
                                                            </span>
                                                          </div>" : null;
                        $productCards.="<img class='product-thumb' src=./media/product_images/$productImg alt=''>
                          </div>
                        </a>
                        <div class='product-card__content'>
                          <a href='product.php?id=$productId' class='product-card__product-link'>
                            $productName
                          </a>
                          <p>$productPrice SEK</p>
                          <button class='add-to-cart-btn'>";
                          $productQty < 1 ? $productCards.= "<i class='far fa-times-circle'></i>" : $productCards.= "<i class='fas fa-cart-plus'></i>";
                          $productCards.="</button>
                          </div>
                      </article>";
  endwhile;
  $productsContainer .= $productCards;
  $productsContainer .= "</div>";

function getHeader($categoryName) {
  if ($categoryName == "") {
    echo "All products" ;
  } else {
    echo $categoryName;
  }
}
?>

<section id="search-section" class="display-products">
  <header class="display-products__header">
    <h2 class="display-products__heading"><?php getHeader($categoryName)?></h2>
  </header>

  <?php
    echo $productsContainer;
  ?>
</section>