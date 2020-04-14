<?php

require_once "../../db.php";
require_once "aside-navigation.php";

$sql = "SELECT * FROM ws_categories";
//Add statement for selecting only categories with products
$stmt = $db->prepare($sql);
$stmt->execute();

$categories = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $name = htmlspecialchars($row['name']);
    $id = htmlspecialchars($row['id']);

    $edit = "<input type='text' value='$name'>";

    $toggle = $name;

    $categories .= "<form action='edit-category.php' method='POST'>
    <tr>
                  <td>
                    <input class='hidden' type='text' name='name' id='input-$id' value='$name'>
                    <p id='category-$id'>$name<p>
                  </td>
                  <td>
                    <button type='button' id='editBtn-$id' onclick='editCategory($id);'>Edit</button>

                      <button class='hidden' type='submit' id='saveBtn-$id'>Save</button>
                      <input type='hidden' name='id' value='$id'>

                  </td>
                  <td>
                    <button>Delete</button>
                  </td>
                </tr>
                </form>";
}

?>

<h1>Categories</h1>

<table>
  <?php echo $categories; ?>
</table>

<script>
function editCategory(id) {
  const categoryP = document.querySelector('#category-' + id);
  categoryP.classList.toggle('hidden');

  const input = document.querySelector('#input-' + id);
  input.classList.toggle('hidden');

  const editBtn = document.querySelector('#editBtn-' + id);
  editBtn.classList.toggle('hidden');

  const saveBtn = document.querySelector('#saveBtn-' + id);
  saveBtn.classList.toggle('hidden');
}
</script>

<style>
  .hidden {
    display: none;
  }
</style>