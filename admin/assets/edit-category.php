<?php

require_once '../../db.php';
require_once './category-validation.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = htmlspecialchars($_POST['id']);
    $newName = htmlspecialchars($_POST['name']);

    if (!validLength($newName)) {

        header('Location: ../category-table.php?invalidlength=true');

    } else if (!onlyValidCharacters($newName)) {

        header('Location: ../category-table.php?invalidchars=true');

    } else {

        try {
            $sql = "UPDATE ws_categories SET name = :name
        WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $newName);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            header('Location: ../category-table.php');
        } catch (\PDOException $e) {
            header('Location: ../category-table.php?editerror=true');
        }

    }

}
