<?php

require_once "../../db.php";
require_once "aside-navigation.php";

$sql = "SELECT * FROM ws_categories";
//Add statement for selecting only categories with products
$stmt = $db->prepare($sql);
$stmt->execute();

$categories = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $name = htmlspecialchars($row['name']);

    $categories .= "<tr>
				                      <th>
				                        $name
				                      </th>
				                      <th>
				                        <form action='#' method'POST'>
				                          <button type='submit'>Edit</button>
				                        </form>
				                      </th>
				                      <th>
				                        <form action='#' method'POST'>
				                          <button type='submit'>Delete</button>
				                        </form>
				                      </th>
				                    </tr>";

endwhile;

?>


<h1>Categories</h1>

<table>
  <tbody>
    <?php echo $categories ?>
  </tbody>
</table>
