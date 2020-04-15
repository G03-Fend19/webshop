<?php
  require_once "./db.php";
  $sql = "SELECT * FROM ws_categories";
  //Add statement for selecting only categories with products
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $categories = "";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    $name = htmlspecialchars($row['name']);
    $id = htmlspecialchars($row['id']);

    $categories .= "<li class='nav-list__item'>
                      <a href='index.php?id=$id#main'>
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