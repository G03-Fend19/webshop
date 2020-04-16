<?php

// Get information from database
require_once "../db.php";
$sql = "SELECT * FROM ws_products";
$stmt = $db->prepare($sql);
$stmt->execute();

// Create dummy products to test (Write out productcards)
$productCards = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  // Save id, name and stock_qty in variables
  $stockQty = htmlspecialchars($row['stock_qty']);
  $id = htmlspecialchars($row['id']);
  $name = htmlspecialchars($row['name']);

  // Add button to dummy product card and if you click on button check stock_qty value
  $stockInfo .= "<section class='stock-" . $id . "'><h2>$name</h2><p>$stockQty</p><button class='product-". $id . "' onclick='alertStockQty($id);'>Press to check</button></section>";  
}

// Bind id and stock_qty together to check stock_qty after id



// Create function that check if stock_qty and return message if it is 0
function alertStockQty($stockQty){
  if ($stockQty < 1){
    echo "<script>alert('The product is sold out at the moment')</script>";
  }
}

echo $productCards;

 ?>

<style>
section {
  padding: 1em;
  border: 2px solid gray;
  border-radius: 25px;
  width: 250px;
  margin: 1em auto;
  text-align: center;
}
</style>