<?php
require_once "../db.php";
require_once "assets/head.php";
require_once "assets/aside-navigation.php";
?>
<main class="admin__products">
  <div class="admin__products__text">
    <h1>Products</h1>
    <div class="main__admin__addCategory">
      <p>Add new</p>
      <i class="fas fa-plus"></i>
    </div>
  </div>

  <div class="admin__products__filter">
    <h2>Filter products</h2>
    <select name="Category" id="filterProd">
      <option value="Fix">Category</option>
      <option value="Fix">Fix</option>
      <option value="Fix">Fix</option>
    </select>
    <input placeholder="search" type="text">
  </div>

  <?php
  $sql = "SELECT    
            ws_products.name        AS ProductName,
            ws_products.description AS ProductDescription,
            ws_products.price       AS ProductPrice,
            ws_products.id          AS ProductId,
            ws_products.stock_qty   AS ProductQty,
            ws_images.img           AS ImageName,
            ws_images.id            AS ImageId,
            ws_categories.name      AS CategoryName,
            ws_categories.id        AS CategoryId
          FROM
            ws_products,
            ws_images,
            ws_products_images,
            ws_categories,
            ws_products_categories
          WHERE
            ws_products.id = ws_products_images.product_id 
          AND
            ws_images.id = ws_products_images.img_id 
          AND
            ws_products.id = ws_products_categories.product_id 
          AND
            ws_categories.id = ws_products_categories.category_id 
            
            GROUP BY ws_products.id
            ";

//ORDER BY ws_products.id ASC

$stmt = $db->prepare($sql);
$stmt->execute();

echo"<table>
<tr>
<th></th>
<th>Product number</th>
<th>Name</th>
<th>Description</th>
<th>Category</th>
<th>Stock qty</th> 
<th>Price</th>
<th> </th> 
<th> </th>
</tr>";

while($row= $stmt->fetch(PDO::FETCH_ASSOC)) :
    $id= htmlspecialchars($row['ProductId']);
    $name= htmlspecialchars_decode($row['ProductName']);
    $description= htmlspecialchars($row['ProductDescription']);
    $stock_qty= htmlspecialchars($row['ProductQty']);
    $price= htmlspecialchars($row['ProductPrice']);
    $image= htmlspecialchars($row['ImageName']);
    $category= htmlspecialchars($row['CategoryName']);
    $descriptionShort = substr($description, 0, 20);
    echo "<tr>
            <td><img src='../media/product_images/$image' alt='placeholder'></td>
            <td>#$id</td>
            <td>$name</td>
            <td>$descriptionShort...</td>
            <td>$category</td>
            <td>$stock_qty st</td>
            <td>$price SEK</td>
            <td><button><i class='fas fa-pen'></i></button></td>
            <td>
                <form action='assets/delete-product.php' onsubmit='return deleteProductConfirm()' method='POST'>
                  <button type='submit'><i class='far fa-trash-alt'></i></button>
                  <input type='hidden' name='id' value='$id'>
               </form>
            </td>
         </tr>";
endwhile;
echo'</table>';
echo '</main>';
?>

  <script>
  function deleteProductConfirm() {
    if (confirm("Are you sure you want to delete this product?")) {
      return true;
    } else {
      return false;
    }
  }
  </script>

  <?php require_once 'assets/foot.php';