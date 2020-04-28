<?php
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);

    //print_r($_POST);
    $images = [];

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["image$i"])) {
            $images += ["image$i" => $_POST["image$i"]];
        }
    }

    $duplicateCheck = "SELECT name FROM ws_products
WHERE name = :title
AND active = 1";

    $stmt = $db->prepare($duplicateCheck);
    $stmt->bindParam(':title', $title);

    $stmt->execute();
    if (empty($title) || empty($description) || $price == "" || $price == null || $qty == "" || $qty == null) {
        header("Location: ../create_product.php?formerror=empty&title=$title&descrip=$description&price=$price&qty=$qty");
        exit();
    } elseif ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        header("Location: ../create_product.php?formerror=duplicate&title=$title&descrip=$description&price=$price&qty=$qty");
        exit();
    } elseif ($category == "category" || empty($category)) {
        header("Location: ../create_product.php?formerror=nocategory&title=$title&descrip=$description&price=$price&qty=$qty");
        exit();
    } elseif ($price < 0 || $qty < 0) {
        header("Location: ../create_product.php?formerror=negative&title=$title&descrip=$description&price=$price&qty=$qty");
        exit();
    }
    ;

//Inserting the new product into db
    $sql1 = "INSERT INTO ws_products (name, description, price, stock_qty)
VALUES (:name, :description, :price, :qty)";

    $stmt1 = $db->prepare($sql1);

    $stmt1->bindParam(':name', $title);
    $stmt1->bindParam(':description', $description);
    $stmt1->bindParam(':price', $price);
    $stmt1->bindParam(':qty', $qty);

    $stmt1->execute();

//Inserting the category relationship
    $sql2 = "INSERT INTO ws_products_categories (product_id, category_id)
VALUES (LAST_INSERT_ID(), :category);
SET @p_id = LAST_INSERT_ID()";

    $stmt2 = $db->prepare($sql2);
    $stmt2->bindParam(':category', $category);
    $stmt2->execute();

//Inserting the images and product img relationship

    if (count($images) != 0) {
        foreach ($images as $index => $img) {
            $sql_img = "INSERT INTO ws_images (img) VALUES (:img)";
            $stmt = $db->prepare($sql_img);
            $stmt->bindParam(":img", $img);
            $stmt->execute();

            $sql_p_img = "INSERT INTO ws_products_images (product_id, img_id)
                    VALUES ( @p_id, LAST_INSERT_ID())";
            $stmt_rel = $db->prepare($sql_p_img);
            $stmt_rel->execute();
        }
    }

}

header("Location: ../products_page.php");