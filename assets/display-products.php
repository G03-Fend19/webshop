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
            ws_products.name          AS ProductName,
            ws_products.price         AS ProductPrice,
            ws_products.id            AS ProductId,
            ws_products.stock_qty     AS ProductQty,
            ws_products.added_date    AS AddedDate,
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
          AND ws_products.stock_qty > 0
          AND ws_products.active = 1";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":category_id", $categoryId);
    $stmt->execute();
} else {
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

// echo "<pre>";
// print_r($results);
// echo "</pre>";

$grouped = [];
$hiddenClass = "hidden";

foreach ($results as $row) {
    // The product id for this row
    $currentProductId = $row["ProductId"];

    // If we've already added this product
    if (isset($grouped[$currentProductId])) {

        // Just add the additional image name to the imgIds array
        $grouped[$currentProductId]["imgNames"][] = $row["ImageName"];
    } else {

        // If we haven't added the product yet
        if (isset($_GET['category_id'])) {
            $grouped[$currentProductId] = [
                "imgNames" => [], // Start with empty
                "ProductId" => $row["ProductId"],
                "ProductName" => $row["ProductName"],
                "ProductPrice" => $row["ProductPrice"],
                "ProductQty" => $row["ProductQty"],
                "CategoryName" => $row["CategoryName"],
                "AddedDate" => $row['AddedDate'],
            ];
        } else {
            $grouped[$currentProductId] = [
                "imgNames" => [], // Start with empty
                "ProductId" => $row["ProductId"],
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
}

// echo "<pre>";
// print_r($grouped);
// echo "</pre>";

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
    } elseif ($product['ProductQty'] < 11 && $product['AddedDate'] <= $lastChanceLimitDate) {
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
    $priceMsg = "<div><span class='original-price'>$productPrice SEK</span>
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

if (empty($product['imgNames'])) {
    $productImg = "placeholder.jpg";
} else {
    $productImg = htmlspecialchars($product['imgNames'][0]);
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
$productCards .= "<img class='product-thumb' src=./media/product_images/$productImg alt=''>
								                          </div>
								                        </a>
                                        <div class='product-card__content'>
                                        <div class='product-card__text'>
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
                                            class='add-to-cart-btn'
                                            id='addToCartBtn-$productId'>
                                          <i class='fas fa-cart-plus'></i>
                                          </button>
                                          <div class='product-section__rigth__actions__amount__qty-container hidden' id='productQty-$productId'>
          <input class='product-section__rigth__actions__amount__qty-container__input' id='qtyInput-$productId' value='1' type='number' min='1' max='<?php echo $$productQty ?>'>
          <div
            data-id=$productId
            data-name='$productName'
            data-price=$productPrice
            data-img='$productImg'
            data-stock=$productQty
            data-discount=$discount
            >

            <button class='product-section__rigth__actions__amount__qty-container__qtyBtn' onclick='lowerQty($productId)'><i class='fas fa-minus-circle'></i></button>
            <button class='product-section__rigth__actions__amount__qty-container__qtyBtn' id='higherBtn' onclick='higherQty($productQty, $productId)'><i class='fas fa-plus-circle'></i></button>
          </div>

        </div>
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

<script>

let grouped = <?php echo json_encode($grouped) ?>;


checkCartProducts(grouped);

function checkCartProducts(grouped) {

  for (let product of Object.values(grouped)) {
  let name = product['ProductName'];
  let id = product['ProductId'];
  let addBtn = document.querySelectorAll('#addToCartBtn-' + id);

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

// document.addEventListener("click", (e) => {
//   const productId = e.target.parentNode.parentNode.parentNode.dataset.name;

//   if (e.target.dataset.id == "qty+") {
//     setTimeout(getCartQty(), 1000);

//   } else if (e.target.dataset.id == "qty-") {
//     setTimeout(getCartQty(), 1000);
//   }
// });

function lowerQty(id) {
  let input = document.getElementById('qtyInput-' + id);

  if (input.value > 1)
  input.value = parseInt(input.value) - 1;
}

function higherQty(qty, id) {
  let input = document.getElementById('qtyInput-' + id);

  if (input.value < qty) {
    input.value = parseInt(input.value) + 1;
  }
  // else{
  //   alert('no more in stock')
  // }
}




  // function checkLocalStorage(pruductName) {
  //   console.log(productName);
  // }
</script>
