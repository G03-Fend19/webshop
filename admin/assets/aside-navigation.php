<?php

$sql = "SELECT * FROM ws_categories";
//Add statement for selecting only categories with products
$stmt = $db->prepare($sql);
$stmt->execute();

$categories = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $name = htmlspecialchars($row['name']);

    $categories .= "<li class='aside__nav__ul__li'>
								                      <h2 class='aside__nav__ul__li__title'>
								                        <a class='aside__nav__ul__li__title__link' href=''>$name</a>
								                      </h2>
								                    </li>";

endwhile;

?>

<aside class="aside">
  <h2 class="aside__h2">
    <a class="aside__h2__link" href="">Dashboard</a>
  </h2>
  <h3 class="aside__title">CREATE</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Add product</a>
        </h2>
      </li>
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Add category</a>
        </h2>
      </li>
    </ul>
  </nav>
  <h3 class="aside__title">PRODUCTS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Show all</a>
        </h2>
      </li>
      <?php echo $categories; ?>
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="./assets/category-table.php">Manage categories</a>
        </h2>
      </li>
    </ul>
  </nav>
  <h3 class="aside__title">ORDERS</h3>
  <nav class="aside__nav">
    <ul class="aside__nav__ul">
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Show all</a>
        </h2>
      </li>
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Pending</a>
        </h2>
      </li>
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Processed</a>
        </h2>
      </li>
      <li class="aside__nav__ul__li">
        <h2 class="aside__nav__ul__li__title">
          <a class="aside__nav__ul__li__title__link" href="">Fulfilled</a>
        </h2>
      </li>
    </ul>
  </nav>
</aside>
