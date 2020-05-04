<?php
$id = "";
$categoryName = "";
$productMsg = "";
$priceMsg = "";
$qtyMsg = "";

$currentDateTime = date('Y-m-d H:i:s');
$currentDateTimeDT = new DateTime($currentDateTime);
$newInLimitDT = $currentDateTimeDT->sub(new DateInterval('P14D'));
$newInLimitDate = $newInLimitDT->format('Y-m-d H:i:s');
$lastChanceLimitDT = $currentDateTimeDT->sub(new DateInterval('P1Y'));
$lastChanceLimitDate = $lastChanceLimitDT->format('Y-m-d H:i:s');

if (isset($_GET['category_id']) && $_GET['category_id'] !== "") {
    require_once "./db.php";
    $categoryId = htmlspecialchars($_GET['category_id']);

    $sql = "SELECT
            ws_products.name            AS ProductName,
            ws_products.price           AS ProductPrice,
            ws_products.id              AS ProductId,
            ws_products.stock_qty       AS ProductQty,
            ws_images.img               AS ImageName,
            ws_products_images.img_id   AS ProductImageImageId,
            ws_products_images.feature  AS FeatureImg,
            ws_categories.id            AS CategoryId,
            ws_categories.name          AS CategoryName
            ws_products.added_date    AS AddedDate,
          FROM
            ws_products
          LEFT JOIN
            ws_products_images
          ON
            ws_products.id = ws_products_images.product_id
          AND
            ws_products_images.feature = 1
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
          AND ws_products.stock_qty > 0
          AND ws_products.active = 1";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":category_id", $categoryId);
    $stmt->execute();
} else {
    $sql = "SELECT
            ws_products.name            AS ProductName,
            ws_products.price           AS ProductPrice,
            ws_products.id              AS ProductId,
            ws_products.stock_qty       AS ProductQty,
            ws_images.img               AS ImageName,
            ws_products_images.img_id   AS ProductImageImageId,
            ws_products_images.feature  AS FeatureImg
            ws_products.added_date    AS AddedDate,
          FROM
            ws_products
          LEFT JOIN
            ws_products_images
          ON
            ws_products.id = ws_products_images.product_id
          AND
            ws_products_images.feature = 1
          LEFT JOIN
            ws_images
          ON
            ws_products_images.img_id = ws_images.id
          WHERE
            ws_products.stock_qty > 0
          AND ws_products.active = 1";

    $stmt = $db->prepare($sql);
    $stmt->execute();
}

$productsContainer = "<div class='products'>";
$productCards = "";

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
// $grouped = [];

// foreach ($results as $row) {
//     // The product id for this row
//     $currentProductId = $row["ProductId"];

//     // If we've already added this product
//     if (in_array($currentProductId, $grouped)) {

//         // Just add the additional image name to the imgIds array
//         $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
//     } else {

//         // If we haven't added the product yet
//         if (isset($_GET['category_id'])) {
//             $grouped[$currentProductId] = [
//                 "imgNames" => [], // Start with empty
//                 "ProductName" => $row["ProductName"],
//                 "ProductPrice" => $row["ProductPrice"],
//                 "ProductQty" => $row["ProductQty"],
//                 "CategoryName" => $row["CategoryName"],
//             ];
//         } else {
//             $grouped[$currentProductId] = [
//                 "imgNames" => [], // Start with empty
//                 "ProductName" => $row["ProductName"],
//                 "ProductPrice" => $row["ProductPrice"],
//                 "ProductQty" => $row["ProductQty"],
//             ];
//         }
  //              "AddedDate" => $row['AddedDate'],
   //             "AddedDate" => $row['AddedDate'],

//         // If there is an image for this row, add it
//         if ($row["ProductImageImageId"]) {
//             $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
//         }

//     }
// }
// echo "<pre>";
// print_r($grouped);
// echo "</pre>";

foreach ($results as $product):
    $productId = $product['ProductId'];
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
    // $productImg = htmlspecialchars($product['ImageName']); // TODO
    if ($product['ImageName'] == NULL) {
        $productImg = "placeholder.jpg";
    } else {
        $productImg = htmlspecialchars($product['ImageName']);
    }

    if (isset($_GET['category_id'])) {
        $categoryName = htmlspecialchars($product['CategoryName']);
    }

    $productCards .= "<article class='product-card'>
								                        <a href='product.php?product_id=$productId#main' class='product-card__image-link'>
		                                      <div class='image-wrapper'>
		                                      $productMsg";

    $productQty < 1 ? $productCards .= "<div class='out-of-stock'>
					                                                            <span class='out-of-stock__msg'>
					                                                            Currently out of stock
					                                                            </span>
					                                                          </div>" : null;
    $productCards .= "<img class='product-thumb' src='./media/product_images/$productImg' alt='product image'>
					                        </a>
					                          </div>
					                        <div class='product-card__content'>
					                          <a href='product.php?product_id=$productId#main' class='product-card__product-link'>
					                            $productName
					                          </a>
                                          $priceMsg
                                          </div>
								                          <button
								                          data-id=$productId
								                          data-name='$productName'
								                          data-price=$productPrice
								                          data-img='$productImg'
                                          data-stock=$productQty
                                          data-discount=$discount 
								                          class='add-to-cart-btn'>";
    $productQty < 1 ? $productCards .= "<i class='far fa-times-circle'></i>" : $productCards .= "<i class='fas fa-cart-plus'></i>";
                          $productCards .= "</button>
                                            $qtyMsg
								                          </div>
                                      </article>";
endforeach;
$productsContainer .= $productCards;
$productsContainer .= "</div>";

function getHeader($categoryName)
{
    if ($categoryName == "") {
        echo "All products";
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
