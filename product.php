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
if (isset($_GET['product_id'])) {
    $productId = htmlspecialchars($_GET['product_id']);
    $sql = "SELECT
            ws_products.name            AS ProductName,
            ws_products.description     AS ProductDescription,
            ws_products.price           AS ProductPrice,
            ws_products.id              AS ProductId,
            ws_products.stock_qty       AS ProductQty,
            ws_images.img               AS ImageName,
            ws_products_images.img_id   AS ProductImageImageId,
            ws_products_images.feature  AS FeatureImg,
            ws_categories.id            AS CategoryId,
            ws_categories.name          AS CategoryName,
            ws_products.added_date    AS AddedDate
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
            ws_products.id = :products_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":products_id", $productId);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo $sql;
    // echo "---<br />";
    // print_r($results);
    // [
    //   [1] => [ <-- key is ProductId
    //     "imgIds" => [
    //        "ImageId" => 3,
    //        "ImageName" => "sfndsjkf.jpg"
    //     ],
    //     "ProductName" => "Korv",
    //     ...
    //   ],
    //   [24] => [
    //   ]
    // ]
    $grouped = [];
    foreach ($results as $currentProductId => $row) {
        // The product id for this row
        $currentProductId = $row["ProductId"];
        // If we've already added this product
        if (isset($grouped[$currentProductId])) {
            // Just add the additional image name to the imgIds array
            $grouped[$currentProductId]["imgs"][] =  [
              "ImageName" => $row['ImageName'],
              "FeatureImg" => $row['FeatureImg'],
            ];
        } else {
            // If we haven't added the product yet
            $grouped[$currentProductId] = [
                "imgs" => [], // Start with empty
                "ProductId" => $currentProductId,
                "ProductName" => $row["ProductName"],
                "ProductDescription" => $row["ProductDescription"],
                "ProductPrice" => $row["ProductPrice"],
                "ProductQty" => $row["ProductQty"],
                "AddedDate" => $row['AddedDate'],
                "CategoryName" => $row["CategoryName"],
            ];
            // If there is an image for this row, add it
            if ($row["ProductImageImageId"]) {
              $grouped[$currentProductId]["imgs"][] =  [
                "ImageName" => $row['ImageName'],
                "FeatureImg" => $row['FeatureImg'],
              ];
            }
        }
    }
    // echo "<pre>";
    //   print_r($grouped);
    //   echo "</pre>";

//     echo "<pre>";
// print_r($grouped);
// echo "</pre>";

    if (empty($grouped)) {
        ?>
      <script type="text/javascript">
      window.location.href = './product_not_found.php';
      </script>
      <?php

    }

    foreach ($grouped as $productId => $product):
      // echo "<pre>";
      // print_r($product['imgs']);
      // echo "</pre>";
        $stmtCheck = $product;
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
        $id = htmlspecialchars($product['ProductId']);
        $name = htmlspecialchars_decode($product['ProductName']);
        $description = htmlspecialchars($product['ProductDescription']);
        $stock_qty = htmlspecialchars($product['ProductQty']);
        if ($stock_qty > 9) {
          $qtyMsg = "<span class='in-store'> $stock_qty in store</span>";
        } else {
          $qtyMsg = "<span class='few-in-store'>Less than 10 in store</span>";
        }
        $price = htmlspecialchars($product['ProductPrice']);
        $discount = 1;
        if($product['ProductQty'] < 10 && $product['AddedDate'] <= $lastChanceLimitDate) {
          $discount = 0.9;
          $discountProductPrice = ceil($price - ($price * 0.1));
          $priceMsg = "<div><span class='original-price'>$price SEK</span>
                        <span class='discount'>$discountProductPrice SEK</span></div>";
        } else {
          $priceMsg = "<span>$price SEK</span>";
        }
        $category = htmlspecialchars($product['CategoryName']);
        $descriptionShort = substr($description, 0, 20);
        if (empty($product['imgs'])) {
            $productImg = "placeholder.jpg";
        } else {
            $imgArray = $product['imgs'];
            foreach ($imgArray as $key => $img) {
              if ($img['FeatureImg'] == 1) {
                $productImg = $img['ImageName'];
              }
            }
            $imgList = $imgArray;
        }
    endforeach;
}
// echo $stock_qty;
// print_r($imgList);
// echo $productImg;
// echo $name;
// echo "<img src='$productImg' alt=''>";

// print_r($grouped);

?>

<section id='product-section' class='product-section'>
  <div class='product-section__images'>
    <div class='img-wrapper'>
      <?php echo $productMsg?>
      <img class='product-section__images__big' src="./media/product_images/<?php echo $productImg ?>" alt="">
    </div>
    <div class='product-section__images__small-container'>
    <i class="fas fa-chevron-left" onclick="prevImg()"></i>
      <div  class='product-section__images__small-container__img-container'>

      <?php
if (!empty($imgList)) {
    foreach ($imgList as $img) {
      $imageName = $img['ImageName'];
        echo "<div class='img-wrapper' ><img class='product-section__images__small-container__img-container__img' onclick=\"changeImg('$imageName')\" src='./media/product_images/$imageName' alt='product image'></div>";
    }
}

?>

      </div>
      <i class="fas fa-chevron-right" onclick="nextImg()"></i>
    </div>
  </div>
  <div class='product-section__rigth'>
    <div class='product-section__rigth__info'>
      <h1 class='product-section__rigth__info__name'><?php echo $name ?></h1>
      <h3 class='product-section__right__info__categories'><?php echo $category ?></h3>
      <h2 class='product-section__rigth__info__price'><?php echo $priceMsg ?></h2>
      <?php echo $qtyMsg?>
    </div>
    <div class='product-section__rigth__actions'>
      <label class='product-section__rigth__actions__lable' for="">Amount</label>
      <div class='product-section__rigth__actions__qty-container'>
        <input class='product-section__rigth__actions__qty-container__input' id='qtyInput' value="1" type="number" min='1' max='<?php echo $stock_qty ?>'>
        <button class='product-section__rigth__actions__qty-container__qtyBtn' onclick='lowerQty()'><i class="fas fa-minus-circle"></i></button>
        <button class='product-section__rigth__actions__qty-container__qtyBtn' id='higherBtn' onclick='higherQty(<?php echo $stock_qty ?>)'><i class="fas fa-plus-circle"></i></button>
      </div>
      <!-- <button type="submit" class="button add-to-cart-btn">Add to basket<i class='fas fa-cart-plus'></i></button> -->
      <div
          data-id=<?php echo $id ?>
          data-name='<?php echo $name ?>'
          data-price=<?php echo $price ?>
          data-img='<?php echo $productImg ?>'
          data-stock=<?php echo $stock_qty ?>
          data-discount=<?php echo $discount ?>
          data-quantity=
          >
        <button class='button add-to-cart-btn'>Add to basket<i class='fas fa-cart-plus'></i></button>
      </div>

    </div>
  </div>
  <div class='product-section__description'>
    <h3>Description</h3>
    <p><?php echo $description ?></p>
  </div>

</section>

<script>

getCartQty();

function getCartQty() {
  const getCart = () => {
    cart = JSON.parse(localStorage.getItem("cart"));
    !cart ? (cart = {}) : null;

  };
  getCart();

  let input = document.getElementById('qtyInput');
  let name = "<?php echo "$name" ?>";
  if (cart[name]) {
    input.value = cart[name].quantity;
  }

}



function lowerQty() {
  let input = document.getElementById('qtyInput');

  if (input.value > 1)
  input.value = input.value - 1;
}

function higherQty(qty) {
  let input = document.getElementById('qtyInput');
  let button = document.getElementById('higherBtn');

  if (input.value < qty) {
    input.value = parseInt(input.value) + 1;
  }else{
    alert('no more in stock')
  }
}



const imgList = <?php echo json_encode($imgList); ?>;
const selectedImg = imgList[0];
let selectedIndex = 0;

function changeImg(img) {
  const bigImg = document.querySelector('.product-section__images__big');

  bigImg.src = './media/product_images/' + img;
  selectedIndex = imgList.findIndex(listImg => listImg === img);
}

function nextImg() {
  const bigImg = document.querySelector('.product-section__images__big');

  if (selectedIndex < imgList.length - 1) {
    selectedIndex++;
    bigImg.src = './media/product_images/' + imgList[selectedIndex];
  }

}

function prevImg() {
  const bigImg = document.querySelector('.product-section__images__big');

  if (selectedIndex > 0 ) {
    selectedIndex--;
    bigImg.src = './media/product_images/' + imgList[selectedIndex];
  }
}

</script>



<?php

require_once "./assets/foot.php";
