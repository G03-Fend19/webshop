<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once '../../db.php';

    $p_id = htmlspecialchars($_POST['product_id']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);

//Updating the product
    $sql = "UPDATE ws_products
              SET
                name = :title,
                description = :description,
                price = :price,
                stock_qty = :qty
              WHERE id = :id;
            UPDATE ws_products_categories
              SET
                category_id = :category_id
              WHERE product_id = :id";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':id', $p_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':qty', $qty);
    $stmt->bindParam(':category_id', $category_id);

    $stmt->execute();
}