<?php
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);

    //print_r($_POST);
    $images = [];

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["image$i"])) {
            $images += ["image$i" => $_POST["image$i"]];
        }

    }

    $duplicateCheck = "SELECT name FROM ws_products
WHERE name = :title
AND active = 1";

    $stmt = $db->prepare($duplicateCheck);
    $stmt->bindParam(':title', $title);
    $stmt->execute();

    $descrip_error=urlencode($description);

    if (empty($title) || empty($description) || $price == "" || $price == null || $qty == "" || $qty == null) {
        header("Location: ../create_product.php?formerror=empty&title=$title&descrip=$descrip_error&price=$price&qty=$qty");
        exit();
    } elseif ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        header("Location: ../create_product.php?formerror=duplicate&title=$title&descrip=$descrip_error&price=$price&qty=$qty");
        exit();
    } elseif ($category == "category" || empty($category)) {
        header("Location: ../create_product.php?formerror=nocategory&title=$title&descrip=$descrip_error&price=$price&qty=$qty");
        exit();
    } elseif ($price < 0 || $qty < 0) {
        header("Location: ../create_product.php?formerror=negative&title=$title&descrip=$descrip_error&price=$price&qty=$qty");
        exit();
    }
    



//Inserting the new product into db
    $sql1 = "INSERT INTO ws_products (name, description, price, stock_qty)
VALUES (:name, :description, :price, :qty)";

    $stmt1 = $db->prepare($sql1);

    $stmt1->bindParam(':name', $title);
    $stmt1->bindParam(':description', $description);
    $stmt1->bindParam(':price', $price);
    $stmt1->bindParam(':qty', $qty);

    $stmt1->execute();

//Inserting the category relationship
    $sql2 = "INSERT INTO ws_products_categories (product_id, category_id)
VALUES (LAST_INSERT_ID(), :category);
SET @p_id = LAST_INSERT_ID()";

    $stmt2 = $db->prepare($sql2);
    $stmt2->bindParam(':category', $category);
    $stmt2->execute();
    $stmt2->nextRowset();

//Inserting the images and product img relationship

    if (count($images) != 0) {
        if (isset($_POST['feature']) && !empty($_POST['feature'])) {
            $featureImg = htmlspecialchars($_POST['feature']);
        } else {
            $featureImg = $images["image1"];
        }
        foreach ($images as $index => $img) {
            $sql_img = "INSERT INTO ws_images (img) VALUES (:img)";
            $stmt = $db->prepare($sql_img);
            $stmt->bindParam(":img", $img);
            $stmt->execute();
            $stmt->nextRowset();

            if ($img == $featureImg) {
                $sql_p_img = "INSERT INTO ws_products_images (product_id, img_id, feature)
VALUES ( @p_id, LAST_INSERT_ID(), 1)";
            } else {
                $sql_p_img = "INSERT INTO ws_products_images (product_id, img_id)
VALUES ( @p_id, LAST_INSERT_ID())";
            }

            $stmt_rel = $db->prepare($sql_p_img);
            $stmt_rel->execute();
            $stmt_rel->nextRowset();
        }
    }


    // Delete all images that aren't used from the server

    $sql_all_images = "SELECT
                            ws_images.img AS imgName
                        FROM
                            ws_images";

    $stmt_all_images = $db->prepare($sql_all_images);
    $stmt_all_images->execute();

    $all_images_array = [];
    while ($row_img = $stmt_all_images->fetch(PDO::FETCH_ASSOC)) {
        $all_images_array[] = $row_img['imgName'];
    }


   /*  echo '<pre>';
    print_r($all_images_array);
    echo '</pre>'; */

    $directory = '../../media/product_images';
    $scanned_image_directory = array_diff(scandir($directory), array('..', '.'));

    if (count($scanned_image_directory) != 0) {
        foreach ($scanned_image_directory as $filename) {

            if (!in_array($filename, $all_images_array)) {
                if ($filename != 'placeholder.jpg') {   
                    unlink("../../media/product_images/$filename");
                }
            }
        }
    }

}

header("Location: ../products_page.php");