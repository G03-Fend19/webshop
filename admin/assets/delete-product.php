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

    // Selecting the product images to delete them from the database and the server folder
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

    $imageIds = [];
    $imageFileNames = [];
    while ($imagesRows = $stmt_img->fetch(PDO::FETCH_ASSOC)) {
        $imageIds[] = $imagesRows["imgId"];
        $imageFileNames[] = $imagesRows["imgName"];

    }

    if (count($imageIds) != 0) {
        // Deleting the relations between the product and its images
        $sqlDeleteRel = "DELETE FROM ws_products_images WHERE product_id = :p_id";
        $stmt_delete_rel = $db->prepare($sqlDeleteRel);
        $stmt_delete_rel->bindParam(':p_id', $id);
        $stmt_delete_rel->execute();

        // Deleting the images from ws_images
        foreach ($imageIds as $img_id) {
            $sqlDeleteImg = "DELETE
                            FROM ws_images
                            WHERE id = :img_id";

            $stmt_delete_img = $db->prepare($sqlDeleteImg);
            $stmt_delete_img->bindParam(':img_id', $img_id);
            $stmt_delete_img->execute();

        }
        foreach ($imageFileNames as $imgFile) {
            unlink("../../media/product_images/$imgFile");
        }

    }

    // Setting the product to not active, so it's "deleted"
    $sql = "UPDATE ws_products SET active = 0
    WHERE id = :id;
    DELETE FROM ws_products_categories
    WHERE product_id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

}

header('Location:../products_page.php');