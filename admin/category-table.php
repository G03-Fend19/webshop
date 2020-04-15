<?php

require_once "../db.php";
require_once "./assets/head.php";
require_once "./assets/aside-navigation.php";

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

    $categories .= "
                      <tr>
                        <td>
                          <form action='edit-category.php' method='POST'>
                            <input class='hidden' type='text' name='name' id='input-$id' value='$name'>
                            <button class='hidden' type='submit' id='saveBtn-$id'>Save</button>
                            <input type='hidden' name='id' value='$id'>
                          </form>
                          <p id='category-$id'>$name<p>
                        </td>
                        <td>
                          <button type='button' id='editBtn-$id' onclick='editCategory($id);'>Edit</button>
                        </td>
                        <td>
                          <form action='delete-category.php' method='POST'>
                            <button type='submit'>Delete</button>
                            <input type='hidden' name='id' value='$id'>
                          </form>
                        </td>
                      </tr>
                    ";
}

if (isset($_GET['error'])) {

    $categories .= "<p class='error'>Was not able to delete category. Please make sure it does not have any relations to products.</p>";

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
</body>

</html>

<!-- <style>
/* .hidden {
  display: none;
}

.error {
  color: red;
} */
</style> -->