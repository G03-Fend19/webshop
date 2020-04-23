<?php
require_once '../db.php';
require_once 'upload_image.php';

$sql = "SELECT * FROM ws_categories";

$stmt = $db->prepare($sql);
$stmt->execute();

$options = "<option value='category' disabled hidden selected
  >Category</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='$row[id]'>$row[name]</option>";

}

$title = isset($_GET['title']) ? $_GET['title'] : '';
$descrip = isset($_GET['descrip']) ? $_GET['descrip'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : null;
$qty = isset($_GET['qty']) ? $_GET['qty'] : null;

require_once './assets/head.php';
require_once './assets/aside-navigation.php';

?>
<section class="new__product__section">
  <h1>Add new product</h1>

  <form id="dragme" class="upload-form hidden" method='post' action='' enctype='multipart/form-data' draggable="true">
    <div class="upload-form__border">
      <button class="cancel-upload" type="button"><i class="fas fa-times-circle"></i></button>
    </div>
    <input type="file" name="file[]" id="file" multiple>
    <input type="hidden" name="p_id" value="<?=$productId?>">
    <input class="upload-btn" type='submit' name='submit' value='Upload'>
  </form>


  <form class="form" id="addProductForm" name="addProductForm" action="./assets/process_product.php"
    onsubmit="return validateProductForm()" method="POST">
    <div class="form__group">
      <label for="title" class="form__label">
        Product name
        <input type="text" name="title" id="title" value="<?=$title?>" minlength="2" maxlength="50" required
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


    <div class="form__image-section">
      <div class="form__image-section__create">
        <p>Images</p>
        <button class="add-img button" type="button">Add images <i class="fas fa-images"></i></button>

      </div>

      <div class="form__image-section__images">
        <?php

$counter = 1;
foreach ($imageArray as $image) {
    echo "
      <label class='form__image-section__selection'>
      $image
      <input class='form__image-section__selection__checkbox' type='checkbox' id='no_img' name='image$counter' value='$image' checked>
      <img class='form__image-section__selection__image thumbnails' src='../media/product_images/$image' class='thumbnails'>
      </label>
      ";
    $counter++;
}

?>
      </div>
    </div>
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
    <button class="button add-product-btn" type="submit">Add Product</button>
  </form>
</section>
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

let productForm = JSON.parse(localStorage.product_form);
if (Object.keys(productForm).length != 0 && productForm.constructor === Object) {
  console.log("hejhej");
  const title = document.forms["addProductForm"]["title"];
  const description = document.forms["addProductForm"]["description"];
  const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"];
  const qty = document.forms["addProductForm"]["qty"];
  const theCategory = document.qyerySelector(`option['value="${category}"]`);

  console.log(theCategory);


  title.value = productForm.title;
  description.value = productForm.description;
  price.value = productForm.price;
  qty.value = productForm.qty;

  localStorage.setItem('product_form', "");
}
</script>
<?php
require_once './assets/foot.php';
?>