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
            ws_products.description LIKE '%$search%')";
  
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $productsContainer = "<div class='products'>";
  $productCards = "";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $productName = htmlspecialchars($row['ProductName']);
    $productPrice = htmlspecialchars($row['ProductPrice']);
    $productImg = htmlspecialchars($row['ImageName']);

    $productCards .= "<article class='product-card'>
                        <a href='' class='product-card__image-link'>
                          <div class='image-wrapper'>
                            <img class='product-thumb' src=./media/product_images/$productImg alt=''>
                          </div>
                        </a>
                        <div class='product-card__content'>
                          <a href='' class='product-card__product-link'>
                            <h3>$productName</h3>
                          </a>
                          <p>$productPrice SEK</p>
                          <button class='add-to-cart-btn'>Add to cart</button>
                        </div>
                      </article>";
  endwhile;
  $productsContainer .= $productCards;
  $productsContainer .= "</div>";

} else {
  // header('Location: index.php');
}

function getHeader($search) {
  if ($search !== "") {
    echo "You searced for '$search' and the result is...";
  } else {
    echo "Unvalid search";
  }
}
?>

<section id="search-section" class="display-products">
  <header class="display-products__header">
    <h2 class="display-products__heading"><?php getHeader($search)?></h2>
  </header>
  <!-- insert search bar and function -->

  <?php
  if ($search !== ""){echo $productsContainer;}
  ?>
</section>