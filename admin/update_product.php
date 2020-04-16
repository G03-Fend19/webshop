<?php
require_once '../db.php';

$productId = "5";

$sql = "SELECT
            ws_products.id AS ProductId,
            ws_products.name AS ProductName,
            ws_products.description AS ProductDescription,
            ws_products.price AS ProductPrice,
            ws_products.stock_qty AS ProductQty,
            ws_categories.id AS CategoryId,
            ws_categories.name AS CategoryName
          FROM
            ws_products,
            ws_images,
            ws_categories,
            ws_products_images,
            ws_products_categories
          WHERE
            ws_products.id = :id
          AND
            ws_products_categories.product_id = :id
          AND
            ws_categories.id = ws_products_categories.category_id";

$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $productId);
$stmt->execute();

$sql_images = "SELECT
                    ws_images.id AS imgId,
                    ws_images.img AS imgName
                  FROM
                    ws_images,
                    ws_products_images
                  WHERE
                    ws_products_images.product_id = :id
                  AND
                    ws_images.id = ws_products_images.img_id";

$stmt_img = $db->prepare($sql_images);
$stmt_img->bindParam(':id', $productId);
$stmt_img->execute();

// Selecting all categories
$sql_categories = "SELECT * FROM ws_categories";
$stmt_categories = $db->prepare($sql_categories);
$stmt_categories->execute();

/* echo "<pre>";
print_r($stmt_categories->fetch(PDO::FETCH_ASSOC));
echo "</pre>"; */

while ($productRow = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $pName = htmlspecialchars($productRow['ProductName']);
    $descrip = htmlspecialchars($productRow['ProductDescription']);
    $categoryId = htmlspecialchars($productRow['CategoryId']);
    $categoryName = htmlspecialchars($productRow['CategoryName']);
    $price = htmlspecialchars($productRow['ProductPrice']);
    $qty = htmlspecialchars($productRow['ProductQty']);

}

$options = "";
while ($categoryRows = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
    if ($categoryRows['id'] == $categoryId) {
        $options .= "<option value='$categoryRows[id]' selected>$categoryRows[name]</option>";
    } else {
        $options .= "<option value='$categoryRows[id]'>$categoryRows[name]</option>";
    }

}

while ($imagesRows = $stmt_img->fetch(PDO::FETCH_ASSOC)) {
    echo $imagesRows['imgName'];
}

//print_r($stmt_img->fetch(PDO::FETCH_ASSOC));

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="./styles/style.css">
</head>

<body>

  <form class="form" id="addProductForm" name="addProductForm" action="./assets/process_product.php"
    onsubmit="return validateProductForm()" method="POST">
    <div class="form__group">
      <label for="title" class="form__label">
        Product name
        <input type="text" name="title" id="title" value="<?=$pName?>" minlength="2" maxlength="50" required
          class="form__input">
      </label>
      <label for="description" class="form__label descrip">
        Description
        <textarea name="description" id="description" maxlength="800" required
          class="form__input"><?=$descrip?></textarea>
      </label>
      <select name="category" id="category">
        <?=$options?>
      </select>
      <label for="price" class="form__label">
        Price
        <input type="number" name="price" id="price" value="<?=$price?>" min="0" required class="form__input">
      </label>
      <label for="qty" class="form__label">
        Qty
        <input type="number" name="qty" id="qty" value="<?=$qty?>" min="0" required class="form__input">
      </label>

    </div>
    <label for="img" class="form__label">Images</label>

    <button type="submit">Add</button>
    <div id="errorDiv">
      <?php

if (!isset($_GET['formerror'])) {

} else {
    $errorCheck = $_GET['formerror'];
    if ($errorCheck == 'duplicate') {

        echo "<p class='errormsg'>You already have a product called <strong>$title</strong>.</p>";

    } elseif ($errorCheck == 'empty') {
        echo "<p class='errormsg'>Please fill in all fields.</p>";

    } elseif ($errorCheck == 'nocategory') {
        echo "<p class='errormsg'>Please select a category for the product.</p>";

    } elseif ($errorCheck == 'negative') {
        echo "<p class='errormsg'>The product price and quantity can't be less than 0.</p>";

    }
}

?>
    </div>
  </form>

  <?php

?>
  <script src="validation.js"></script>

</body>

</html>