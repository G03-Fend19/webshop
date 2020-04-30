<?php

$sql = "SELECT * FROM ws_categories";
$stmt = $db->prepare($sql);
$stmt->execute();

$categories = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $id = htmlspecialchars($row['id']);
    $name = htmlspecialchars($row['name']);
    if(isset($_GET['category_id'])){
      $categoryTitle = htmlspecialchars($_GET['category_id']);
    }
    $class = "aside__nav__ul__li__title__link";
    $arrow = "";
    if (isset($_GET['category_id']) && $categoryTitle == $id) {
      $class .= "-active"; 
      $arrow = '<i class="fas fa-chevron-right"></i>';
    } 
    else {$class = "aside__nav__ul__li__title__link";}
    
    $categories .= "<li class='aside__path aside__nav__ul__li'>
					                      <i class='far fa-star'></i>
                                <a class='$class' href='./products_page.php?category_id=$id'>$name</a>
                                $arrow
					  									</li>";
endwhile;

?>

<aside class="aside">
  <div class="aside__path aside__dashboard">
    <i class="fas fa-home"></i>
    <a class="name__link-dashboard" href="index.php">Dashboard</a>
  </div>
  <h3 class="aside__title">CREATE</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-plus"></i>
        <a class="aside__nav__ul__li__title__addProduct" href="./create_product.php">Add product</a>
      </li>
      <li class="aside__path">
        <i class="fas fa-plus"></i>
        <a class="aside__nav__ul__li__title__addCategory" href="./category-table.php?addCategory=true">Add category</a>
      </li>
    </ul>
  </nav>
  <h3 class="aside__title">PRODUCTS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-shopping-basket"></i>
        <a class="name__link-products" href="./products_page.php">Show
          all</a>
      </li>
      <?php echo $categories; ?>
      <li class="aside__path">
        <i class="fas fa-wrench"></i>
        <a class="name__link-manage" href="./category-table.php">Manage categories</a>
      </li>
    </ul>
  </nav>
  <h3 class="aside__title">ORDERS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__path">
        <i class="fas fa-shopping-cart"></i>
        <a class="orders__link-showAll" href=" ./orders_page.php">Show all</a>
      </li>
      <li class="aside__path">
        <i class="far fa-hourglass"></i>
        <a class="orders__link-activeOrders" href=" ./orders_page.php?orders=active">Active</a>
      </li>
      <li class="aside__path">
        <i class="fas fa-check"></i>
        <a class="orders__link-completed" href="
          ./orders_page.php?orders=completed">Completed</a>
      </li>
    </ul>
  </nav>
</aside>

<!-- <script>
const addCategory = document.querySelector('.aside__nav__ul__li__title__addCategory');
const addCategoryForm = document.querySelector('.aside__nav__ul__li__title__form');
const saveNewCategoryBtn = document.querySelector('#saveBtn');

addCategory.addEventListener('click', () => {
  addCategoryForm.classList.toggle("hidden")
})

saveNewCategoryBtn.addEventListener('click', () => {
  addCategoryForm.classList.toggle("hidden")
})
</script> -->

<style>
.hidden {
  display: none;
}
</style>