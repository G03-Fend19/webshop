<?php
$search = "";

if (isset($_GET['search']) && $_GET['search'] !== "") {
  require_once "./db.php";
  $search = htmlspecialchars($_GET['search']);

  $sql = "SELECT
            ws_products.name        AS ProductName,
            ws_products.description AS ProductDescription,
            ws_products.price       AS ProductPrice,
            ws_products.id          AS ProductId,
            ws_products.stock_qty    AS ProductQty,
            ws_images.img           AS ImageName,
            ws_images.id            AS ImageId
          FROM
            ws_products,
            ws_images,
            ws_products_images
          WHERE
            ws_products.id = ws_products_images.product_id 
          AND
            ws_images.id = ws_products_images.img_id 
          AND
            (ws_products.name LIKE '%$search%' 
          OR
            ws_products.description LIKE '%$search%')
          GROUP BY ws_products.id";
  
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $productsContainer = "<div class='products'>";
  $productCards = "";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $productName = ucfirst(htmlspecialchars($row['ProductName']));
    $productPrice = htmlspecialchars($row['ProductPrice']);
    $productId = htmlspecialchars($row['ProductId']);
    $productImg = htmlspecialchars($row['ImageName']);
    $productQty = htmlspecialchars($row['ProductQty']);

    // if($productQty < 1) {
    //   $productCards .= "<div class='out-of-stock'>"
    // }

    $productCards .= "<article class='product-card'>
                        <a href='product.php?id=$productId' class='product-card__image-link'>
                          <div class='image-wrapper'>";
                        $productQty < 1 ? $productCards.= "<div class='out-of-stock'>
                                                            <span class='out-of-stock__msg'>
                                                            Product currently out of stock
                                                            </span>
                                                          </div>" : null;
                        $productCards.="<img class='product-thumb' src=./media/product_images/$productImg alt=''>
                          </div>
                        </a>
                        <div class='product-card__content'>
                          <a href='product.php?id=$productId' class='product-card__product-link'>
                            <h3>$productName</h3>
                          </a>
                          <p>$productPrice SEK</p>
                          <button class='add-to-cart-btn'>";
                          $productQty < 1 ? $productCards.= "Out of stock" : $productCards.= "Add to cart";
                          $productCards.="</button>
                          </div>
                      </article>";
  endwhile;
  $productsContainer .= $productCards;
  $productsContainer .= "</div>";

} else {
  header('Location: index.php');
}

function getSearchHeader($search) {
  if ($search !== "") {
    echo "You searced for '$search' and the result is...";
  } else {
    echo "Unvalid search";
  }
}
?>

<section id="search-section" class="display-products">
  <header class="display-products__header">
    <h2 class="display-products__heading"><?php getSearchHeader($search)?></h2>
  </header>

  <?php
  if ($search !== ""){echo $productsContainer;}
  ?>
</section>