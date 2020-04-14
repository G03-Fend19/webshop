<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);
    $img = 'placeholder.jpg';

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