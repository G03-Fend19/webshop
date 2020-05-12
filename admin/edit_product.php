<?php
require_once '../db.php';
require_once 'upload_image.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['formerror'])) {
    $productId = htmlspecialchars($_POST['p_id']);

    $sql = 'SELECT
            ws_products.id AS ProductId,
            ws_products.name AS ProductName,
            ws_products.description AS ProductDescription,
            ws_products.price AS ProductPrice,
            ws_products.stock_qty AS ProductQty,
            ws_categories.id AS CategoryId,
            ws_categories.name AS CategoryName
          FROM
            ws_products,
            ws_images,
            ws_categories,
            ws_products_images,
            ws_products_categories
          WHERE
            ws_products.id = :id
          AND
            ws_products_categories.product_id = :id
          AND
            ws_categories.id = ws_products_categories.category_id';

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $productId);
    $stmt->execute();

    $sql_images = 'SELECT
                    ws_images.id AS imgId,
                    ws_images.img AS imgName,
                    ws_products_images.feature AS featureImg
                  FROM
                    ws_images,
                    ws_products_images
                  WHERE
                    ws_products_images.product_id = :id
                  AND
                    ws_images.id = ws_products_images.img_id';

    $stmt_img = $db->prepare($sql_images);
    $stmt_img->bindParam(':id', $productId);
    $stmt_img->execute();

    // Selecting all categories
    $sql_categories = 'SELECT * FROM ws_categories';
    $stmt_categories = $db->prepare($sql_categories);
    $stmt_categories->execute();

    while ($productRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $p_id = htmlspecialchars($productRow['ProductId']);
        $pName = htmlspecialchars($productRow['ProductName']);
        $descrip = htmlspecialchars($productRow['ProductDescription']);
        $categoryId = htmlspecialchars($productRow['CategoryId']);
        $categoryName = htmlspecialchars($productRow['CategoryName']);
        $price = htmlspecialchars($productRow['ProductPrice']);
        $qty = htmlspecialchars($productRow['ProductQty']);
    }

    if (isset($_GET['formerror'])) {
        $p_id = htmlspecialchars($_GET['id']);
        $pName = htmlspecialchars($_GET['title']);
        $descrip = htmlspecialchars($_GET['descrip']);
        $categoryId = htmlspecialchars($_GET['category']);
        $price = htmlspecialchars($_GET['price']);
        $qty = htmlspecialchars($_GET['qty']);
        $imagesGet = unserialize($_GET['images']);
    }

    $options = '';
    while ($categoryRows = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
        if ($categoryRows['id'] == $categoryId) {
            $options .= "<option value='$categoryRows[id]' selected>$categoryRows[name]</option>";
        } else {
            $options .= "<option value='$categoryRows[id]'>$categoryRows[name]</option>";
        }
    }

    $imagesDb = [];
    while ($imagesRows = $stmt_img->fetch(PDO::FETCH_ASSOC)) {
        $imagesDb[] = [
            'img' => $imagesRows['imgName'],
            'feature' => $imagesRows['featureImg']];
    }

} elseif (!isset($_GET['formerror']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location:products_page.php');
}

require_once './assets/head.php';
require_once './assets/aside-navigation.php';

?>

<main class="admin__tables">

  <form id="dragme" class="upload-form hidden" method='post' action='' enctype='multipart/form-data' draggable="true">
    <div class="upload-form__border"> <button class="cancel-upload" type="button">X</button> </div>
    <input type="file" name="file[]" id="file" multiple>
    <input type="hidden" name="p_id" value="<?=$productId;?>">
    <input class="upload-btn" type='submit' name='submit' value='Upload' id="upload-btn">
  </form>


  <h1 class='headline__php'>Editing: Product <?="#$p_id"?></h1>

  <!--   <?php print_r($imagesDb);?> -->

  <form class="form" id="addProductForm" name="addProductForm" action="./assets/process_product_edit.php"
    onsubmit="return validateProductForm()" method="POST">
    <div class="form__group">
      <label for="title" class="form__label">
        Product name
        <input type="text" name="title" id="title" value="<?=$pName;?>" minlength="2" maxlength="50" required
          class="form__input">
      </label>
      <label for="description" class="form__label descrip">
        Description
        <textarea name="description" id="description" maxlength="800" required
          class="form__input"><?=$descrip;?></textarea>
      </label>
      <select name="category" id="category">
        <?=$options;?>
      </select>
      <label for="price" class="form__label">
        Price
        <input type="number" name="price" id="price" value="<?=$price?>" min="0" step=".01" required
          class="form__input">
      </label>
      <label for="qty" class="form__label">
        Qty
        <input type="number" name="qty" id="qty" value="<?=$qty;?>" min="0" required class="form__input">
      </label>

    </div>

    <input type="hidden" name="product_id" value="<?=$p_id;?>">
    <div class="form__image-section">
      <div class="form__image-section__create">
        <p>Upload images</p>
        <p class="images-max hide-images-max">You can only upload 5 images</p>
        <button class="add-img button" type="button">Add images <i class="fas fa-images"></i></button>

      </div>

      <label for="qty" class="form__label">
      Click to choose feature image
        <input type="hidden" name="feature" id="feature" value=""  class="form__input">
      </label>
        <div id="update-product-images" class="form__image-section__images">

        <script>

          const showMaxImgMessage = (arr) => {
            const imageMaxMessage = document.querySelector('.images-max')
            if (arr.length == 5) {
              imageMaxMessage.classList.remove("hide-images-max");
            } else {
              imageMaxMessage.classList.add("hide-images-max");
            }
          };


       const setImagesToLocalStorage = () => {

          // 1. grab images from db
          let imagesFromDb = <?php echo json_encode($imagesDb); ?> ;

          // 2. Grab all images from localStorage, create array if null
          imagesFromLocalStorage = JSON.parse(localStorage.getItem("images"));
          !imagesFromLocalStorage ? imagesFromLocalStorage = [] : null;
          showMaxImgMessage(imagesFromLocalStorage)
          // 3. check deleted
           deletedImages = JSON.parse(localStorage.getItem('deleted'));
          !deletedImages ? deletedImages = [] : null;


          // 3. Push all images from db to localStorage
          if (imagesFromLocalStorage.length > 0) {
            imagesFromDb.forEach((imgObj, index) => {
              if(!deletedImages.includes(imgObj.img) || !imagesFromLocalStorage.includes(imgObj)){
                Object.values(imagesFromLocalStorage[index]).indexOf(imgObj.img) > -1 ? null : imagesFromLocalStorage.push(imgObj); 
              } 

            });

          }
          else {

            imagesFromDb.forEach((imgObj, index) => {
              if(!deletedImages.includes(imgObj.img) || !imagesFromLocalStorage.includes(imgObj)){                
                imagesFromLocalStorage.push(imgObj);
              }
            });
          }

          localStorage.setItem("images", JSON.stringify(imagesFromLocalStorage));

            showMaxImgMessage(imagesFromLocalStorage)

        }
        setImagesToLocalStorage()

        const renderImagesToDOM = () => {
          const updateImageSection = document.getElementById('update-product-images')

          deletedImages = JSON.parse(localStorage.getItem('deleted'));
          !deletedImages ? deletedImages = [] : null;
          updateImageSection.innerHTML = "";

          let counter = 0
          if (imagesFromLocalStorage.length > 0) {

            updateImageSection.innerHTML = ''
            imagesFromLocalStorage.map(imgObj => {
           
              if (!deletedImages.includes(imgObj['img'])) {

              updateImageSection.innerHTML += `
                      <label class='form__image-section__selection' for='image${counter}'>
                      <div class="product-img">
                          <input id='image${counter}' class='form__image-section__selection__radio' type='checkbox' name='image${counter}'
                              checked value='${imgObj['img']}'>
                          <img class='form__image-section__selection__image thumbnails ${imgObj.feature == 1 ? 'featured-image' : ''}' src='../media/product_images/${imgObj['img']}'
                              data-imgname='${imgObj['img']}' class='thumbnails '>

                      </label>
                      <button data-name='${imgObj['img']}' type="button"class="remove-image">x</button>
                      </div>`
                      ;
              counter++


              }
            });

            const feature = document.getElementById('feature');

            imagesFromLocalStorage.forEach(imgObj => {

              if (imgObj['feature'] == 1) {
                feature.value = imgObj['img']
              }

            });
          }

        }

        renderImagesToDOM()

        const deleteImages = () => {
         document.addEventListener("click", (e) => {

          if (e.target.className == "remove-image") {



            // get deletedImages from localstorage
            deletedImages = JSON.parse(localStorage.getItem("deleted"))
            !deletedImages ? deletedImages = [] : null
            imagesFromLocalStorage = JSON.parse(localStorage.getItem("images"))
            // push deleted image to localstorage
            deletedImages.push(e.target.dataset.name)
            localStorage.setItem("deleted", JSON.stringify(deletedImages));
            // remove image from image array
            images = imagesFromLocalStorage.filter((el)=> {
              return el.img !== e.target.dataset.name
            })


            localStorage.setItem("images", JSON.stringify(images));

          }
            // setImagesToLocalStorage()
            renderImagesToDOM();
        });
      }

      deleteImages();


      document.addEventListener('click', e => {
        const feature = document.getElementById('feature');
        if(e.target.classList.contains('form__image-section__selection__image')) {



          imagesFromLocalStorage.forEach(imgObj => {
            if (imgObj['img'] == e.target.dataset.imgname) {
              imgObj['feature'] = 1;

            }
            else {
              imgObj['feature'] = 0;
            }

          });
          localStorage.setItem("images", JSON.stringify(imagesFromLocalStorage));

        }

        renderImagesToDOM()



      });

      document.addEventListener("click", (e) => {
        if (e.target.className == "remove-image") {


          let imagesFromLocalStorage = JSON.parse(localStorage.getItem("images"))

          images = imagesFromLocalStorage.filter((el) => {
            return el !== e.target.dataset.name
          })
          localStorage.setItem("images", JSON.stringify(images));
        }
        renderImagesToDOM()
        });


        </script>
      </div>

      </div>
      <div id="errorDiv">
        <?php

if (count($failedUploads) !== 0) {
    foreach ($failedUploads as $file_name => $errorArray) {
        foreach ($errorArray as $error) {
            echo "<p class='errormsg'><strong>$file_name:</strong> $error</p>";
        }

    }

}

if (!isset($_GET['formerror'])) {
} else {
    $errorCheck = $_GET['formerror'];
    if ($errorCheck == 'duplicate') {
        echo "<p class='errormsg'>You already have a product called <strong>$pName</strong>.</p>";
    } elseif ($errorCheck == 'empty') {
        echo "<p class='errormsg'>Please fill in all fields.</p>";
    } elseif ($errorCheck == 'nocategory') {
        echo "<p class='errormsg'>Please select a category for the product.</p>";
    } elseif ($errorCheck == 'negative') {
        echo "<p class='errormsg'>The product price and quantity can't be less than 0.</p>";
    }
}

?>
      </div>
      <button id="saveBtn" type="submit">Save</button>
  </form>
</main>
<script src="functions.js"></script>
<script>
let uploaded = false;
uploadBtn = document.querySelector('.upload-btn');
uploadBtn.addEventListener('click', () => {
  const title = document.forms["addProductForm"]["title"].value;
  const description = document.forms["addProductForm"]["description"].value;
  const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"].value;
  const qty = document.forms["addProductForm"]["qty"].value;


  let product = {
    title: title,
    description: description,
    category: category,
    price: price,
    qty: qty
  };
  localStorage.setItem('product_form', JSON.stringify(product));

});

if (JSON.parse(localStorage.getItem('product_form'))) {
  let productForm = JSON.parse(localStorage.getItem('product_form'));

  const title = document.forms["addProductForm"]["title"];
  const description = document.forms["addProductForm"]["description"];
  //const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"];
  const qty = document.forms["addProductForm"]["qty"];
  const theCategory = document.querySelector(`option[value="${productForm.category}"]`);


  let options = document.querySelector("#category").options;

  for (var i = 0; i < options.length; i++) {
    options[i].selected = false;
  }

  theCategory.selected = true;


  title.value = productForm.title;
  description.value = productForm.description;
  price.value = productForm.price;
  qty.value = productForm.qty;

  localStorage.removeItem('product_form');
}


window.onbeforeunload = function(event){
 event.preventDefault = true; 
  
  if (document.activeElement.id != "saveBtn" && document.activeElement.id != "upload-btn") {
     localStorage.removeItem('images');
      localStorage.removeItem('deleted');
  }
};




</script>
<?php

require_once './assets/foot.php';