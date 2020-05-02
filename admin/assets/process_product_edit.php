<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once '../../db.php';

    $p_id = htmlspecialchars($_POST['product_id']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);
    $qty = htmlspecialchars($_POST['qty']);
    $featureImg = htmlspecialchars($_POST['feature']);

   /*  echo "Feature: " . $featureImg; */

    // Backend validation
    $duplicateCheck = "SELECT name FROM ws_products
                        WHERE name = :title
                        AND active = 1
                        AND id <> :p_id";

    $stmt_duplicate = $db->prepare($duplicateCheck);
    $stmt_duplicate->bindParam(':title', $title);
    $stmt_duplicate->bindParam(':p_id', $p_id);

    $stmt_duplicate->execute();

    $images = [];

    for ($i = 0; $i < 5; $i++) {
        if (isset($_POST["image$i"])) {
            $images += ["image$i" => $_POST["image$i"]];
        }
    }
   
  /*   echo "images: <pre>";
    print_r($images);
    echo "</pre>"; */

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
    $stmt_current_img->nextRowset();
   

 /*    echo 'current images: <pre>';
    print_r($current_images);
    echo '</pre>'; */

    // Inserting the new images to the database
    if (count($images) > 0) {

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
                $stmt_rel->nextRowset();
            }
        }

    }
    // Deleting the connection to the images that aren't used anymore
    if (count($current_images) != 0) {
        foreach ($current_images as $img_id => $current_img) {

            if (!in_array($current_img, $images)) {

                $sql_delete = "DELETE
                            FROM ws_products_images
                            WHERE img_id = :img_id
                            AND product_id = :product_id";

                $stmt_delete = $db->prepare($sql_delete);
                $stmt_delete->bindParam(':img_id', $img_id);
                $stmt_delete->bindParam(':product_id', $p_id);
                $stmt_delete->execute();
                $stmt_delete->nextRowset();
                
            }
        }
       
    }

// Setting feature-img
    $sql_new_images_db = "SELECT
                                ws_images.id AS imgId,
                                ws_images.img AS imgName
                            FROM
                                ws_images,
                                ws_products_images
                            WHERE
                                ws_products_images.product_id = :product_id
                            AND
                                ws_images.id = ws_products_images.img_id";

/* echo $p_id;    */

    $stmt_new_images_db = $db->prepare($sql_new_images_db);
    $stmt_new_images_db->bindParam(':product_id', $p_id);
    $stmt_new_images_db->execute();
    

    $new_images_db = [];
    while ($img_row = $stmt_new_images_db->fetch(PDO::FETCH_ASSOC)) {
        $new_images_db[$img_row['imgId']] = $img_row['imgName'];
      /*   echo "ny bild från db<br>" . $img_row['imgId'] ."<br>". $img_row['imgName']; */
    }

  /*   echo 'new images db: <pre>';
    print_r($new_images_db);
    echo '</pre>'; */


    foreach ($new_images_db as $img_id => $img_filename) {

        if ($img_filename == $featureImg) {
            $sql_update_feature = "UPDATE ws_products_images
            SET feature = 1
            WHERE product_id = :p_id
            AND img_id = :img_id";
        }
        else {
            $sql_update_feature = "UPDATE ws_products_images
            SET feature = 0
            WHERE product_id = :p_id
            AND img_id = :img_id";
        }
        $stmt_update_feature = $db->prepare($sql_update_feature);
        $stmt_update_feature->bindParam(':p_id', $p_id);
        $stmt_update_feature->bindParam(':img_id', $img_id);
        $stmt_update_feature->execute();

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

    foreach ($scanned_image_directory as $filename) {

        if (!in_array($filename, $all_images_array)) {
            unlink("../../media/product_images/$filename");
        }
    }
    
   /*  echo '<pre>';
    print_r($scanned_image_directory);
    echo '</pre>'; */
}



/*  header("Location:../products_page.php");  */