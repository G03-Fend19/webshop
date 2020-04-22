<?php
require_once "../db.php";

$sql = "SELECT (SELECT COUNT(ws_products.id ) FROM ws_products) AS NumberOfProducts,
       (SELECT COUNT(ws_categories.id) FROM ws_categories) AS NumberOfCategories";

$stmt = $db->prepare($sql);
$stmt->execute();


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $NumberOfProducts = htmlspecialchars($row['NumberOfProducts']);
    $NumberOfCategories = htmlspecialchars($row['NumberOfCategories']);

}

?>

<main class="main__admin__dashboard">

  <div class="main__admin__text">
    <h1>Dashboard</h1> 
  </div>
    <div class="dashboard__box">
        <h2 class="tracking-in-expand"><?php echo $NumberOfProducts; ?></h2>
        <h3 class="tracking-in-expand">Products</h3>
    </div>
   
   <div class="dashboard__box">
       <h2 class="tracking-in-expand" ><?php echo $NumberOfCategories; ?></h2>
       <h3 class="tracking-in-expand">Categories</h3>
    </div>

   <div class="dashboard__box">
       <h2 class="tracking-in-expand">0</h2>
       <h3 class="tracking-in-expand">Orders</h3>
  </div> 
 

</main>
