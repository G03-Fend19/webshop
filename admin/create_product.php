<?php
require_once '../db.php';

$sql = "SELECT * FROM ws_categories";

$stmt = $db->prepare($sql);
$stmt->execute();

$options = "<option value='category' disabled selected>Category</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='$row[name]'>$row[name]</option>";

}

?>


<form action="" method="POST">
  <label for="title">
    <input type="text" name="title" id="title" placeholder="Product name" required>
  </label>
  <label for="description">
    <textarea type="text" name="description" id="description" placeholder="Description" required></textarea>
  </label>

  <select name="category" id="category" required>
    <?=$options?>
  </select>

  <label for="price">
    <input type="number" name="price" id="price" required>
  </label>
  <label for="qty">
    <input type="number" name="qty" id="qty" required>
  </label>
  <label for="img">
    <input type="file" name="img" id="img">
  </label>
  <button type="submit">Add</button>


</form>