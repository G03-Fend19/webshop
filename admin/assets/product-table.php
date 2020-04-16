<?php
/**************************************
 * 
 * Filnamn: product-table.php
 * Författare: Lova Duvnäs
 * Date: 2020-04-15
 * 
 * 1. Filen visar en tabell över
 *    alla produkter i databasen
 *************************************/

?>

<h2>Produkter</h2>

<?php

require_once "../../db.php";
require_once "aside-navigation.php";

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
ORDER BY ws_products.id ASC
";

$stmt = $db->prepare($sql);
$stmt->execute();


echo"<table>";
echo"<tr>
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
    <td><img src='../../media/product_images/$image' alt='placeholder'></td>
    <td>#$id</td>
    <td>$name</td>
    <td>$descriptionShort...</td>
    <td>$category</td>
    <td>$stock_qty st</td>
    <td>$price SEK</td>
    <td><a href='''>Edit</i></a></td>
    <td><a href='delete-product.php?id=$id''> Delete</a>  </td>
          </tr>";
  
endwhile;

echo'</table>';

?>