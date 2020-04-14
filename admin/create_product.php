<?php
require_once '../db.php';

$sql = "SELECT * FROM ws_categories";

$stmt = $db->prepare($sql);
$stmt->execute();

$options = "<option value='category' disabled selected hidden>Category</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='$row[id]'>$row[name]</option>";

}

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
  <script src="validation.js"></script>

  <form name="addProductForm" action="process_product.php" onsubmit="return validateProductForm()" method="POST">
    <label for="title">
      <input type="text" name="title" id="title" placeholder="Product name" required>
    </label>
    <label for="description">
      <textarea name="description" id="description" placeholder="Description" required></textarea>
    </label>
    <select name="category" id="category">
      <?=$options?>
    </select>
    <label for="price">
      <input type="number" name="price" id="price" placeholder="Price" required>
    </label>
    <label for="qty">
      <input type="number" name="qty" id="qty" placeholder="Quantity" required>
    </label>
    <label for="img">
      <input type="file" name="img" id="img">
    </label>
    <button type="submit">Add</button>
  </form>


</body>

</html>