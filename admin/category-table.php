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

    $categories .= "
                      <tr class='main__category-table__tr'>
                        <td class='main__category-table__tr__td-category'>
                          <form action='./assets/edit-category.php' method='POST' class='main__category-table__tr__td__save-form'>
                            <input class='hidden main__category-table__tr__td__save-form__input' type='text' name='name' id='input-$id' value='$name'>
                            <button class='hidden main__category-table__tr__td__save-form__btn' type='submit' id='saveBtn-$id'><i class='fas fa-check'></i></button>
                            <input type='hidden' name='id' value='$id'>
                          </form>
                          <p  class='main__category-table__tr__td__p' id='category-$id'><i class='far fa-star'></i>$name</p>
                        </td>
                        <td class='main__category-table__tr__td smallerBtn'>
                          <button type='button' id='editBtn-$id' class='main__category-table__tr__td__edit-btn' onclick=\"toggleEditCategory('$id', '$name')\"><i class='fas fa-pen'></i></button>
                          <button type='button' id='abortBtn-$id' class='hidden main__category-table__tr__td__cancle-btn' onclick='toggleEditCategory($id)'><i class='fas fa-times'></i></button>
                          <form action='./assets/delete-category.php' method='POST' class='main__category-table__tr__td__delete-form'>
                            <button type='submit' class='main__category-table__tr__td__delete-form__delete-Btn'><i class='far fa-trash-alt'></i></button>
                            <input type='hidden' name='id' value='$id'>
                          </form>
                        </td>
                      </tr>
                    ";
}

if (isset($_GET['deleteerror'])) {

    $categories .= "<p class='error'>Was not able to delete category. Please make sure it does not have any relations to products.</p>";

}

if (isset($_GET['editerror'])) {

    $categories .= "<p class='error'>Was not able to edit category. Can not have the same name as other.</p>";

}

if (isset($_GET['addingerror'])) {

    $categories .= "<p class='error'>Was not able to add category. Can not have the same name as other.</p>";

}

$categories .= "</table>";

?>

<main class="main__admin">

  <div class="main__admin__text">
    <h1>Categories</h1>
    <div class="main__admin__addCategory">
      <p>Add new</p>
      <i class="fas fa-plus"></i>
    </div>
  </div>

  <table cellspacing="10" class="main__category-table">
    <?php echo $categories; ?>
  </table>

  <script>
  function toggleEditCategory(id, name) {

    const categoryP = document.querySelector('#category-' + id);
    categoryP.classList.toggle('hidden');

    console.log(id);
    console.log(name);

    const input = document.querySelector('#input-' + id);
    input.classList.toggle('hidden');

    if (name) {
      input.value = name;
    }

    const editBtn = document.querySelector('#editBtn-' + id);
    editBtn.classList.toggle('hidden');

    const saveBtn = document.querySelector('#saveBtn-' + id);
    saveBtn.classList.toggle('hidden');

    const abortBtn = document.querySelector('#abortBtn-' + id);
    abortBtn.classList.toggle('hidden');
  }
  </script>
</main>

<?php
require_once './assets/foot.php';