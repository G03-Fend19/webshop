<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once '../../db.php';

    $p_id = htmlspecialchars($_POST['product_id']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);

    // Backend validation
    $duplicateCheck = "SELECT name FROM ws_products
                      WHERE name = :title
                        AND id <> :p_id";

    $stmt_duplicate = $db->prepare($duplicateCheck);
    $stmt_duplicate->bindParam(':title', $title);
    $stmt_duplicate->bindParam(':p_id', $p_id);

    $stmt_duplicate->execute();

    $images = [];

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["image$i"])) {
            $images += ["image$i" => $_POST["image$i"]];
        }
    }

    $stringImages = serialize($images);

    if (empty($title) || empty($description) || empty($category_id) || $price == "" || $price == null || $qty == "" || $qty == null) {
        header("Location: ../edit_product.php?formerror=empty&id=$p_id&title=$title&descrip=$description&category=$category_id&price=$price&qty=$qty&images=$stringImages");
        exit();
    } elseif ($stmt_duplicate->fetch(PDO::FETCH_ASSOC)) {
        header("Location: ../edit_product.php?formerror=duplicate&id=$p_id&title=$title&descrip=$description&category=$category_id&price=$price&qty=$qty&images=$stringImages");
        exit();
    } elseif ($price < 0 || $qty < 0) {
        header("Location: ../edit_product.php?formerror=negative&id=$p_id&title=$title&descrip=$description&category=$category_id&price=$price&qty=$qty&images=$stringImages");
        exit();
    }
    ;

//Updating the product and category-relationship
    // $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);

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
    $stmt->nextRowset();

//Updating images

    // select current imgages
    $sql_current_img = "SELECT
                        ws_images.id AS imgId,
                        ws_images.img AS imgName
                      FROM
                        ws_images,
                        ws_products_images
                      WHERE
                        ws_products_images.product_id = :id
                      AND
                        ws_images.id = ws_products_images.img_id";

    $stmt_current_img = $db->prepare($sql_current_img);
    $stmt_current_img->bindParam(':id', $p_id);
    $stmt_current_img->execute();

    $current_images = [];
    while ($imagesRow = $stmt_current_img->fetch(PDO::FETCH_ASSOC)) {
        $current_images[$imagesRow['imgId']] = $imagesRow['imgName'];
    }

    /* echo '<pre>';
    print_r($current_images);
    echo '</pre>'; */

    if (count($images) != 0) {

        foreach ($images as $index => $new_img) {

            if (!in_array($new_img, $current_images)) {

                $sql_img = "INSERT INTO ws_images (img) VALUES (:img)";
                $stmt_img = $db->prepare($sql_img);
                $stmt_img->bindParam(":img", $new_img);
                $stmt_img->execute();

                $sql_p_img = "INSERT INTO ws_products_images (product_id, img_id)
                              VALUES ( $p_id, LAST_INSERT_ID())";
                $stmt_rel = $db->prepare($sql_p_img);
                $stmt_rel->execute();
            }
        }

    }
    if (count($current_images) != 0) {
        foreach ($current_images as $img_id => $current_img) {

            if (!in_array($current_img, $images)) {

                $sql_delete = "DELETE
                            FROM ws_products_images
                            WHERE img_id = :img_id;
                            AND product_id = :product_id";

                $stmt_delete = $db->prepare($sql_delete);
                $stmt_delete->bindParam(':img_id', $img_id);
                $stmt_delete->bindParam(':product_id', $p_id);
                $stmt_delete->execute();
            }
        }
    }

}
header("Location:../products_page.php");