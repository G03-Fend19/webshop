<?php
  
  function checkStockQty(){
    require_once "../db.php";
    $sql = "SELECT * FROM ws_products";
    $stmt = $db->prepare($sql);
    $stmt->execute();
  
    // $stockInfo = "";
  
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $stockQty = htmlspecialchars($row['stock_qty']);
  
    if ($stockQty < 1){
      // $stockInfo .= "<li> Product: $id . Stock: No products in stock</li>";
      echo "<script>alert('The product is sold out at the moment')</script>";
    }
  
    // else {
    //   $stockInfo .= "<li> Product: $id . Stock: $stockQty</li>";
    // }
  }

  }

  // Use this function to check stockQty:
  checkStockQty()
  ?>