<?php
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = htmlspecialchars($_POST['id']);

    try {
        $sql = "DELETE FROM ws_categories
    WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        header('Location: ../category-table.php');
    } catch (\PDOException $e) {
        header('Location: ../category-table.php?deleteerror=true');
    }
}
