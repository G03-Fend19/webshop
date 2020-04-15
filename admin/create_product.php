<?php
require_once '../db.php';

$sql = "SELECT * FROM ws_categories";

$stmt = $db->prepare($sql);
$stmt->execute();

$options = "<option value='category' disabled selected hidden>Category</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='$row[id]'>$row[name]</option>";

}

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



</head>

<body>

  <!-- <script src="validation.js"></script> -->


  <form id="addProductForm" name="addProductForm" action="process_product.php" onsubmit="return validateProductForm()"
    method="POST">
    <label for="title">
      Product name
      <input type="text" name="title" id="title" value="<?=$title?>" required>
    </label>
    <label for="description">
      Description
      <textarea name="description" id="description" required><?=$descrip?></textarea>
    </label>
    <select name="category" id="category">
      <?=$options?>
    </select>
    <label for="price">
      Price
      <input type="number" name="price" id="price" value="<?=$price?>" required>
    </label>
    <label for="qty">
      Qty
      <input type="number" name="qty" id="qty" value="<?=$qty?>" required>
    </label>
    <label for="img">Images</label>
    <input type="file" name="img" id="img">
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

    }
}

?>
    </div>
  </form>
  <script src="validation.js"></script>



</body>

</html>