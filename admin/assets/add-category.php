<?php

require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];

    try {
        $sql = "INSERT INTO ws_categories (name)
        VALUES (:name)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        header('Location:../index.php');
    } catch (\PDOException $e) {
        header('Location: ../category-table.php?addingerror=true');
    }

    
}
