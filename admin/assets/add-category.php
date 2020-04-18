<?php

require_once '../../db.php';
require_once './category-validation.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];

    if (!validLength($name)) {

        header('Location: ../category-table.php?invalidlength=true');

    } else if (!onlyValidCharacters($name)) {

        header('Location: ../category-table.php?invalidchars=true');

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
