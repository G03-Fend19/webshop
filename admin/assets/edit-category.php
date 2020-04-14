<?php

require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = htmlspecialchars($_POST['id']);
    $newName = htmlspecialchars($_POST['name']);

    $sql = "UPDATE ws_categories SET name = :name
    WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $newName);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
}

header('Location: category-table.php');
