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
$lastChanceLimitDT = $currentDateTimeDT->sub(new DateInterval('P1Y'));
$lastChanceLimitDate = $lastChanceLimitDT->format('Y-m-d H:i:s');

$sql = "SELECT
ws_products.name          AS ProductName,
ws_products.price         AS ProductPrice,
ws_products.id            AS ProductId,
ws_products.stock_qty     AS ProductQty,
ws_products.added_date    AS AddedDate,
ws_images.img             AS ImageName,
ws_products_images.img_id AS ProductImageImageId,
ws_products_images.feature  AS FeatureImg
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
ws_products.stock_qty < 11
AND
ws_products.added_date <= :lastChanceLimitDate
AND ws_products.active = 1
AND ws_products.stock_qty > 0";

$stmt = $db->prepare($sql);
$stmt->bindParam(":lastChanceLimitDate", $lastChanceLimitDate);
$stmt->execute();

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
//     if (isset($grouped[$currentProductId])) {

//         // Just add the additional image name to the imgIds array
//         $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
//     } else {
//         // If we haven't added the product yet
//         $grouped[$currentProductId] = [
//             "imgNames" => [], // Start with empty
//             "ProductId" => $row["ProductId"],
//             "ProductName" => $row["ProductName"],
//             "ProductPrice" => $row["ProductPrice"],
//             "ProductQty" => $row["ProductQty"],
//             "AddedDate" => $row['AddedDate'],
//         ];
//     }
//     // If there is an image for this row, add it
//     if ($row["ProductImageImageId"]) {
//         $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
//     }
// }
// echo "<pre>";
// print_r($grouped);
// echo "</pre>";

foreach ($results as $productId => $product):
    $productId = $product['ProductId'];
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
$discount = 1;
if ($product['ProductQty'] < 10 && $product['AddedDate'] <= $lastChanceLimitDate) {
    $discount = 0.9;
    $discountProductPrice = ceil($productPrice - ($productPrice * 0.1));
    $priceMsg = "<div class='price_display'><span class='original-price'>$productPrice SEK</span>
                    <span class='discount'>$discountProductPrice SEK</span></div>";
} else {
    $priceMsg = "<span>$productPrice SEK</span>";
}

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

if (empty($product['ImageName'])) {
    $productImg = "placeholder.jpg";
} else {
    $productImg = htmlspecialchars($product['ImageName']);
}
$productCards .= "
<article class='product-card'>
  <a href='product.php?product_id=$productId#main' class='product-card__image-link'>
    <div class='image-wrapper'>
      <div class='out-of-stock'>
        <span class='out-of-stock__msg'>
          10% off
        </span>
      </div>
      <img class='product-thumb' src=./media/product_images/$productImg alt=''>
    </div>
  </a>
  <div class='product-card__content'>
    <div class='product-card__text'>
      <a href='product.php?product_id=$productId#main' class='product-card__product-link'>
        $productName
      </a>
      $priceMsg
      $qtyMsg
    </div>
    <button data-id=$productId class='add-to-cart-btn' id='addToCartBtn-$productId'>
      <i class='fas fa-cart-plus'data-id=$productId></i>
    </button>
    <div class='amount hidden' id='productQty-$productId' data-id='$productId'>

      <input type='number' min='1' data-productId=$productId class='cart__product__info__btns__qty qty-input amount__input' value>
      <div class='amount__btns' data-id=$productId data-name='$productName' data-price=$productPrice
        data-img='$productImg' data-stock=$productQty data-discount=$discount>

        <button class='amount__btns-minus changeQty' data-productId=$productId data-value='-1'>
          <i data-id='qty-' data-productId=$productId data-value='-1' class='changeQty fas fa-minus-circle'></i>
        </button>
        <button class='amount__btns-plus changeQty' id='higherBtn' data-productId=$productId data-value='1>
          <i data-id='qty+' data-productId=$productId data-value='1'
            class='changeQty fas fa-plus-circle open-modal'></i>
        </button>
      </div>

</div>

                          </div>
                      </article>";
endforeach;
$productsContainer .= $productCards;
$productsContainer .= "</div>";

?>

<section id="search-section" class="display-products">
<header class="display-products__header">
<h2 class="display-products__heading">Last chance</h2>
</header>

<?php
echo $productsContainer;
?>
</section>

<!-- <script>


let grouped = <?php echo json_encode($results) ?>;

checkCartProducts(grouped);

function checkCartProducts(grouped) {

  for (let product of Object.values(grouped)) {
  let name = product['ProductName'];
  let id = product['ProductId'];
  let addBtn = document.querySelectorAll('#addToCartBtn-' + id);

  console.log(id);
  console.log(name);



  addBtn.forEach((btn) =>
    btn.addEventListener("click", (e) => {

      setTimeout(function(){
      checkLocalStorage(name, id);
    }, 100);
    })
  );

  checkLocalStorage(name, id);
  }
}


function checkLocalStorage(name, id) {

  let addToCartBtn = document.querySelector('#addToCartBtn-' + id);
  let qtyBtns = document.querySelector('#productQty-' + id);
  let qtyInput = document.querySelector('#qtyInput-' + id);
  cart = JSON.parse(localStorage['cart']);




  if (name in cart) {

    qtyBtns.classList.remove("hidden");
    addToCartBtn.classList.add("hidden");


    qtyInput.value = cart[name].quantity;

  } else {

    qtyBtns.classList.add("hidden");
    addToCartBtn.classList.remove("hidden");
  }
}

//When deleting a spesific product from cart
document.addEventListener("click", (e) => {
  // const productId = e.target.parentNode.parentNode.parentNode.dataset.name;
  const input = document.getElementById('qtyInput');

  if (e.target.dataset.id == "delete-product") {

    for (let product of Object.values(grouped)) {
      let name = product['ProductName'];
      let id = product['ProductId'];



      setTimeout(function(){
      checkLocalStorage(name, id);
      }, 100);

    }
  }
});

//When clearing cart
document.addEventListener("click", (e) => {
  const input = document.getElementById('qtyInput');
  if (e.target.className == "clear-cart" && !Object.entries(cart).length == 0) {
    for (let product of Object.values(grouped)) {
      let name = product['ProductName'];
      let id = product['ProductId'];

      setTimeout(function(){
      checkLocalStorage(name, id);
      }, 100);

    }
  }
});





</script> -->


<?php require_once "./assets/foot.php";?>