<?php
require_once "./db.php";
require_once "./assets/header.php";
require_once "./assets/categories-menu.php";

$productMsg = "";
$priceMsg = "";
$qtyMsg = "";

$currentDateTime = date('Y-m-d H:i:s');
$currentDateTimeDT = new DateTime($currentDateTime);
$newInLimitDT = $currentDateTimeDT->sub(new DateInterval('P14D'));
$newInLimitDate = $newInLimitDT->format('Y-m-d H:i:s');

// echo $newInLimitDate;

$sql = "SELECT
            ws_products.name          AS ProductName,
            ws_products.price         AS ProductPrice,
            ws_products.id            AS ProductId,
            ws_products.stock_qty     AS ProductQty,
            ws_products.added_date    AS AddedDate,
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
            ws_products.stock_qty > 0
          AND ws_products.added_date >= :newInLimitDate
          AND ws_products.active = 1";

$stmt = $db->prepare($sql);
$stmt->bindParam(":newInLimitDate", $newInLimitDate);
$stmt->execute();

$productsContainer = "<div class='products'>";
$productCards = "";

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$grouped = [];

foreach ($results as $row) {
  
    // The product id for this row
    $currentProductId = $row["ProductId"];
    // If we've already added this product
    if (isset($grouped[$currentProductId])) {
        // Just add the additional image name to the imgIds array
        $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
    } else {
        // If we haven't added the product yet
        $grouped[$currentProductId] = [
            "imgNames" => [], // Start with empty
            "ProductName" => $row["ProductName"],
            "ProductPrice" => $row["ProductPrice"],
            "ProductQty" => $row["ProductQty"],
            "AddedDate" => $row['AddedDate'],
        ];
    }
    // If there is an image for this row, add it
    if ($row["ProductImageImageId"]) {
        $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
    }
}

foreach ($grouped as $productId => $product):
  $productMsg = "";
    $priceMsg = "";
    $qtyMsg = "";
    if ($product['AddedDate'] >= $newInLimitDate) {
        $productMsg = "<div class='new-in'>
		                        <span class='new-in__msg'>
		                        New In
		                        </span>
		                      </div>";
    } elseif ($product['ProductQty'] < 10 && $product['AddedDate'] <= $lastChanceLimitDate) {
      $productMsg = "<div class='out-of-stock'>
                          <span class='out-of-stock__msg'>
                            10% off
                          </span>
                        </div>";
    }

    $productPrice = htmlspecialchars($product['ProductPrice']);
    $priceMsg = "<span>$productPrice SEK</span>";


    $productName = htmlspecialchars($product['ProductName']);
    if (strlen($productName) > 20) {
        $productName = substr($productName, 0, 20) . "...";
    }

    $productQty = htmlspecialchars($product['ProductQty']);
    if ($productQty > 9) {
      $qtyMsg = "<span class='in-store'> $productQty in store</span>";
    } else {
      $qtyMsg = "<span class='few-in-store'>Less than 10 in store</span>";
    }

    if (empty($product['imgNames'])) {
        $productImg = "placeholder.jpg";
    } else {
        $productImg = htmlspecialchars($product['imgNames'][0]);
    }
    // if (isset($_GET['category_id'])) {
    //     $categoryName = htmlspecialchars($product['CategoryName']);
    // }

    $productCards .= "<article class='product-card'>
															                        <a href='product.php?product_id=$productId#main' class='product-card__image-link'>
															                          <div class='image-wrapper'>
					                                                <div class='new-in'>
				                                                    <span class='new-in__msg'>
				                                                    New In
				                                                    </span>
				                                                  </div>
					                                                <img class='product-thumb' src=./media/product_images/$productImg alt=''>
															                          </div>
															                        </a>
															                        <div class='product-card__content'>
															                          <a href='product.php?product_id=$productId#main' class='product-card__product-link'>
															                            $productName
															                          </a>
															                          $priceMsg
															                          <button
															                          data-id=$productId
															                          data-name='$productName'
															                          data-price=$productPrice
															                          data-img='$productImg'
															                          data-stock=$productQty
															                          class='add-to-cart-btn'>";
    $productQty < 1 ? $productCards .= "<i class='far fa-times-circle'></i>" : $productCards .= "<i class='fas fa-cart-plus'></i>";
    $productCards .= "</button>
                                                        $qtyMsg
															                          </div>
															                      </article>";
endforeach;
$productsContainer .= $productCards;
$productsContainer .= "</div>";
?>

<section id="search-section" class="display-products">
  <header class="display-products__header">
    <h2 class="display-products__heading">New In</h2>
  </header>

  <?php
echo $productsContainer;
?>
</section>

<?php

require_once "./assets/foot.php";