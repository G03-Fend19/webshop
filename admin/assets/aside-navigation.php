<?php

$sql = "SELECT * FROM ws_categories";
//Add statement for selecting only categories with products
$stmt = $db->prepare($sql);
$stmt->execute();

$categories = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $id = htmlspecialchars($row['id']);
    $name = htmlspecialchars($row['name']);

    $categories .= "<li class='aside__path aside__nav__ul__li'>
                      <i class='far fa-star'></i>
                      <a class='aside__nav__ul__li__title__link' href=''>$name</a>
  									</li>";

endwhile;

?>

<aside class="aside">
  <div class="aside__path aside__dashboard">
    <i class="fas fa-home"></i>
    <a class="aside__h2__link" href="">Dashboard</a>
    <i class="fas fa-chevron-right"></i>
  </div>
  <h3 class="aside__title">CREATE</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-plus"></i>
        <a class="aside__nav__ul__li__title__link" href="">Add product</a>
      </li>
      <li class="aside__path">
        <i class="fas fa-plus"></i>
        <a class="aside__nav__ul__li__title__addCategory" href="#">Add category</a>
        <form class="hidden aside__nav__ul__li__title__form" action='./assets/add-category.php' method='POST'>
          <input type='text' name='name' id=''>
          <button type='submit' id='saveBtn'>Save</button>
        </form>

      </li>
    </ul>
  </nav>
  <h3 class="aside__title">PRODUCTS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-shopping-basket"></i>
        <a class="aside__nav__ul__li__title__link" href="./assets/product-table.php">Show all</a>
      </li>
      <?php echo $categories; ?>
      <li class="aside__path">
        <i class="fas fa-wrench"></i>
        <a class="aside__nav__ul__li__title__link" href="./category-table.php">Manage categories</a>
      </li>
    </ul>
  </nav>
  <h3 class="aside__title">ORDERS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-shopping-cart"></i>
        <a class="aside__nav__ul__li__title__link" href="">Show all</a>
      </li>
      <li class="aside__path">
        <i class="far fa-hourglass"></i>
        <a class="aside__nav__ul__li__title__link" href="">Pending</a>
      </li>
      <li class="aside__path">
        <i class="fas fa-wrench"></i>
        <a class="aside__nav__ul__li__title__link" href="">Processed</a>
      </li>
      <li class="aside__path">
        <i class="fas fa-check"></i>
        <a class="aside__nav__ul__li__title__link" href="">Completed</a>
      </li>
    </ul>
  </nav>
</aside>

<script>
const addCategory = document.querySelector('.aside__nav__ul__li__title__addCategory');
const addCategoryForm = document.querySelector('.aside__nav__ul__li__title__form');
const saveNewCategoryBtn = document.querySelector('#saveBtn');

addCategory.addEventListener('click', () => {
  addCategoryForm.classList.toggle("hidden")
})

saveNewCategoryBtn.addEventListener('click', () => {
  addCategoryForm.classList.toggle("hidden")
})
</script>

<style>
.hidden {
  display: none;
}
</style>