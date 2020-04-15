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
            $images[] = $_POST["image$i"];
        }
    }

    /*  print_r($images);

    INSERT INTO sales.promotions (
    promotion_name,
    discount,
    start_date,
    expired_date
    )
    VALUES
    (
    '2019 Summer Promotion',
    0.15,
    '20190601',
    '20190901'
    ), */

    $duplicateCheck = "SELECT name FROM ws_products
WHERE name = :title";

    $stmt = $db->prepare($duplicateCheck);
    $stmt->bindParam(':title', $title);

    $stmt->execute();
    if (empty($title) || empty($description) || empty($price) || empty($qty)) {
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
VALUES (LAST_INSERT_ID(), :category)";

    $stmt2 = $db->prepare($sql2);
    $stmt2->bindParam(':category', $category);
    $stmt2->execute();

}