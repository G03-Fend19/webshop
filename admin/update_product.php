<?php

$productId = 59;

//$sql = "SELECT"

$title = isset($_GET['title']) ? $_GET['title'] : '';
$descrip = isset($_GET['descrip']) ? $_GET['descrip'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : null;
$qty = isset($_GET['qty']) ? $_GET['qty'] : null;

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