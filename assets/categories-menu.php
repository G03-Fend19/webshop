<?php
  require_once "./db.php";
  $sql = "SELECT
            ws_categories.name AS CategoryName,
            ws_categories.id   AS CategoryId
          FROM
            ws_categories, ws_products, ws_products_categories
          WHERE
            ws_categories.id = ws_products_categories.category_id
          GROUP BY ws_categories.id";
  //Add statement for selecting only categories with products
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $categories = "<li class='nav-list__item'>
                  <a href='index.php#main'>
                    <img src=''>
                    <span>All products<span>
                  </a>
                </li>";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $name = htmlspecialchars($row['CategoryName']);
    $categoryId = htmlspecialchars($row['CategoryId']);

    $categories .= "<li class='nav-list__item'>
                      <a href='index.php?category_id=$categoryId#main'>
                        <img src=''>
                        <span>$name<span>
                      </a>
                    </li>";
  endwhile
?>

<section class="categories-menu">
  <div class="categories-menu__container">
    <h2>Categories</h2>
    <nav>
      <ul class="nav-list">
      <?php echo $categories; ?>
      </ul>
    </nav>
  </div>
</section>