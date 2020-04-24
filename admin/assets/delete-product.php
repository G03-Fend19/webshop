<?php

require_once '../../db.php';

function deleteConfirm()
{
    if (confirm("Are you sure you want to delete this product?")) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = htmlspecialchars($_POST['id']);

    // Selecting the product images to delete them from the folder on the server
    $sql_images = "SELECT
    ws_images.id AS imgId,
    ws_images.img AS imgName
    FROM
    ws_images,
    ws_products_images
    WHERE
    ws_products_images.product_id = :id
    AND
    ws_images.id = ws_products_images.img_id";

    $stmt_img = $db->prepare($sql_images);
    $stmt_img->bindParam(':id', $id);
    $stmt_img->execute();

    $sql = 'DELETE FROM ws_products WHERE id = :id;
  DELETE FROM ws_images WHERE id = :id';

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($imagesRows = $stmt_img->fetch(PDO::FETCH_ASSOC)) {
        //$imagesDb[] = $imagesRows['imgName'];
        unlink("../../media/product_images/$imagesRows[imgName]");
    }

}

header('Location:../products_page.php');