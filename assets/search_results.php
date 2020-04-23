<?php
$search = "";

if (isset($_GET['search']) && $_GET['search'] !== "") {
  require_once "./db.php";
  $search = htmlspecialchars($_GET['search']);

  $sql = "SELECT 
            ws_products.name          AS ProductName, 
            ws_products.price         AS ProductPrice,
            ws_products.id            AS ProductId,
            ws_products.stock_qty     AS ProductQty,
            ws_images.img             AS ImageName,
            ws_products_images.img_id AS ProductImageImageId
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
          WHERE
            (ws_products.name LIKE '%$search%' 
          OR
            ws_products.description LIKE '%$search')
          AND ws_products.stock_qty > 0";
  
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $productsContainer = "<div class='products'>";
  $productCards = "";

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

  foreach($results as $row) {
    // The product id for this row
    $currentProductId = $row["ProductId"];

    // If we've already added this product
    if(in_array($currentProductId, $grouped)) {

      // Just add the additional image name to the imgIds array
      $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
    } else {

      // If we haven't added the product yet
      $grouped[$currentProductId] = [
        "imgNames" => [], // Start with empty
        "ProductName" => $row["ProductName"],
        "ProductPrice" => $row["ProductPrice"],
        "ProductQty" => $row["ProductQty"],
      ];

      // If there is an image for this row, add it
      if($row["ProductImageImageId"]) {
        $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
      }
    }
  }

  foreach($grouped as $productId => $product):
    $productName = htmlspecialchars($product['ProductName']);
    $productPrice = htmlspecialchars($product['ProductPrice']);
    $productQty = htmlspecialchars($product['ProductQty']);
    // $productImg = htmlspecialchars($product['ImageName']); // TODO
    if (empty($product['imgNames'])) {
      $productImg = "placeholder.jpg";
    } else {
      $productImg = htmlspecialchars($product['imgNames'][0]);
    }

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
                          <button 
                          	 data-id=$productId
		                          data-name='$productName'
		                          data-price=$productPrice
		                          data-img='$productImg'
		                          data-stock=$productQty
                          class='add-to-cart-btn'>";
                          $productQty < 1 ? $productCards.= "<i class='far fa-times-circle'></i>" : $productCards.= "<i class='fas fa-cart-plus'></i>";
                          $productCards.="</button>
                          </div>
                      </article>";
  endforeach;
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
  <header class="display-products__header" id="searchResult">
    <h2 class="display-products__heading"><?php getSearchHeader($search)?></h2>
  </header>

  <?php
  if ($search !== ""){echo $productsContainer;}
  ?>
</section>