<?php
require_once '../db.php';
require_once 'upload_image.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['formerror'])) {

    $productId = htmlspecialchars($_POST['p_id']);

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
        $p_id = htmlspecialchars($productRow['ProductId']);
        $pName = htmlspecialchars($productRow['ProductName']);
        $descrip = htmlspecialchars($productRow['ProductDescription']);
        $categoryId = htmlspecialchars($productRow['CategoryId']);
        $categoryName = htmlspecialchars($productRow['CategoryName']);
        $price = htmlspecialchars($productRow['ProductPrice']);
        $qty = htmlspecialchars($productRow['ProductQty']);

    }

    if (isset($_GET['formerror'])) {
        $p_id = htmlspecialchars($_GET['id']);
        $pName = htmlspecialchars($_GET['title']);
        $descrip = htmlspecialchars($_GET['descrip']);
        $categoryId = htmlspecialchars($_GET['category']);
        $price = htmlspecialchars($_GET['price']);
        $qty = htmlspecialchars($_GET['qty']);
        $imagesGet = unserialize($_GET['images']);
    }

    $options = "";
    while ($categoryRows = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
        if ($categoryRows['id'] == $categoryId) {
            $options .= "<option value='$categoryRows[id]' selected>$categoryRows[name]</option>";
        } else {
            $options .= "<option value='$categoryRows[id]'>$categoryRows[name]</option>";
        }

    }

    $imagesDb = [];
    while ($imagesRows = $stmt_img->fetch(PDO::FETCH_ASSOC)) {
        $imagesDb[] = $imagesRows['imgName'];
    }
    if (isset($_FILES['file']['name'])) {
        if (count($_FILES) != 0) {
            foreach ($imageArray as $image) {
                $imagesDb[] = $image;
            }

        }
    }

//print_r($stmt_img->fetch(PDO::FETCH_ASSOC));
} elseif (!isset($_GET['formerror']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:products_page.php");

}

require_once './assets/head.php';
require_once './assets/aside-navigation.php';

?>

<main class="admin__products">

  <form id="dragme" class="upload-form hidden" method='post' action='' enctype='multipart/form-data' draggable="true">
    <div class="upload-form__border"> <button class="cancel-upload" type="button">X</button> </div>
    <input type="file" name="file[]" id="file" multiple>
    <input type="hidden" name="p_id" value="<?=$productId?>">
    <input class="upload-btn" type='submit' name='submit' value='Upload'>
  </form>


  <h1>Editing: Product <?="#$p_id"?></h1>



  <form class="form" id="addProductForm" name="addProductForm" action="./assets/process_product_edit.php"
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
        <input type="number" name="price" id="price" value="<?=$price?>" min="0" step=".01" required
          class="form__input">
      </label>
      <label for="qty" class="form__label">
        Qty
        <input type="number" name="qty" id="qty" value="<?=$qty?>" min="0" required class="form__input">
      </label>

    </div>

    <input type="hidden" name="product_id" value="<?=$p_id?>">

    <div class="form__image-section">
      <label for="img" class="form__label">Images</label>
      <div class="form__image-section__create">
        <button class="add-img button" type="button">Add Images</button>

      </div>

      <div class="form__image-section__images">
        <?php

if (count($imagesDb) != 0) {

    $counter = 1;
    foreach ($imagesDb as $image) {
        echo "
          <label class='form__image-section__selection'>
          $image
          <input class='form__image-section__selection__checkbox' type='checkbox' id='no_img' name='image$counter' value='$image' checked>
          <img class='form__image-section__selection__image thumbnails' src='../media/product_images/$image' class='thumbnails'>
          </label>
          ";
        $counter++;
    }
} else {
    $counter = 1;
    foreach ($imagesGet as $image) {
        echo "
        <label class='form__image-section__selection'>
        $image
        <input class='form__image-section__selection__checkbox' type='checkbox' id='no_img' name='image$counter' value='$image' checked>
        <img class='form__image-section__selection__image thumbnails' src='../media/product_images/$image' class='thumbnails'>
        </label>
        ";
        $counter++;
    }

}
?>
      </div>
      <button type="submit">Save</button>
      <div id="errorDiv">
        <?php

if (!isset($_GET['formerror'])) {

} else {
    $errorCheck = $_GET['formerror'];
    if ($errorCheck == 'duplicate') {

        echo "<p class='errormsg'>You already have a product called <strong>$pName</strong>.</p>";

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

</main>
<script src="functions.js"></script>
<script>
let uploaded = false;
uploadBtn = document.querySelector('.upload-btn');
uploadBtn.addEventListener('click', () => {
  const title = document.forms["addProductForm"]["title"].value;
  const description = document.forms["addProductForm"]["description"].value;
  const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"].value;
  const qty = document.forms["addProductForm"]["qty"].value;


  let product = {
    title: title,
    description: description,
    category: category,
    price: price,
    qty: qty
  };
  localStorage.setItem('product_form', JSON.stringify(product));

});

if (JSON.parse(localStorage.getItem('product_form'))) {
  let productForm = JSON.parse(localStorage.getItem('product_form'));

  const title = document.forms["addProductForm"]["title"];
  const description = document.forms["addProductForm"]["description"];
  //const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"];
  const qty = document.forms["addProductForm"]["qty"];
  const theCategory = document.querySelector(`option[value="${productForm.category}"]`);


  let options = document.querySelector("#category").options;

  for (var i = 0; i < options.length; i++) {
    options[i].selected = false;
  }

  theCategory.selected = true;


  title.value = productForm.title;
  description.value = productForm.description;
  price.value = productForm.price;
  qty.value = productForm.qty;

  localStorage.removeItem('product_form');
}
</script>
<?php

require_once './assets/foot.php';