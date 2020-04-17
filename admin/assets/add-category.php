<?php

require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];

    if (!preg_match("/^[a-zA-ZäöåÄÖÅ]+$/", $name)) {

        header('Location: ../category-table.php?invalidchars=true');

    } else if (strlen($name) < 2 || strlen($name) > 20) {

        header('Location: ../category-table.php?invalidlength=true');

    } else {

        try {
            $sql = "INSERT INTO ws_categories (name)
            VALUES (:name)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            header('Location: ../category-table.php');
        } catch (\PDOException $e) {
            header('Location: ../category-table.php?addingerror=true');
        }

    }
}
