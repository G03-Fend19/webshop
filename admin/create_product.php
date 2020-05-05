<?php
require_once '../db.php';
require_once 'upload_image.php';

$sql = "SELECT * FROM ws_categories";

$stmt = $db->prepare($sql);
$stmt->execute();

$options = "<option value='category' disabled hidden selected
  >Category</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='$row[id]'>$row[name]</option>";

}

$title = isset($_GET['title']) ? $_GET['title'] : '';
$descrip = isset($_GET['descrip']) ? $_GET['descrip'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : null;
$qty = isset($_GET['qty']) ? $_GET['qty'] : null;

require_once './assets/head.php';
require_once './assets/aside-navigation.php';

?>
<section class="new__product__section">
  <h1 class="headline__php">Add new product</h1>

  <form id="dragme" class="upload-form hidden" method='post' action='' enctype='multipart/form-data' draggable="true">
    <div class="upload-form__border">
      <button class="cancel-upload" type="button"><i class="fas fa-times-circle"></i></button>
    </div>
    <input type="file" name="file[]" id="file" multiple>
    <input type="hidden" name="p_id" value="<?=$productId?>">
    <input class="upload-btn" type='submit' name='submit' value='Upload'>
  </form>


  <form class="form" id="addProductForm" name="addProductForm" action="./assets/process_product.php"
    onsubmit="return validateProductForm()" method="POST">
    <div class="form__group">
      <label for="title" class="form__label">
        Product name
        <input type="text" name="title" id="title" value="<?=$title?>" minlength="2" maxlength="50" required
          class="form__input">
      </label>
      <label for="description" class="form__label descrip">
        Description
        <textarea name="description" id="description" maxlength="800" required
          class="form__input"><?=$descrip?></textarea>
      </label>
      <select name="category" id="category">
        <?=$options?>
      </select>
      <label for="price" class="form__label">
        Price
        <input type="number" name="price" id="price" value="<?=$price?>" min="0" required class="form__input">
      </label>
      <label for="qty" class="form__label">
        Qty
        <input type="number" name="qty" id="qty" value="<?=$qty?>" min="0" required class="form__input">
      </label>

    </div>


    <div class="form__image-section">
      <div class="form__image-section__create">
        <p>Upload images</p>
        <p class="images-max hide-images-max">You can only upload 5 images</p>
        <button class="add-img button" type="button">Add images <i class="fas fa-images"></i></button>

      </div>

      <label for="qty" class="form__label">
        Click to choose feature image
        <input type="hidden" name="feature" id="feature" value="" class="form__input">
      </label>
      <div class="form__image-section__images">
        
        <script>
          const imageSection = document.querySelector('.form__image-section__images')

          const showMaxImgMessage = (arr) => {
            const imageMaxMessage = document.querySelector('.images-max')
            if (arr.length == 5) {
              imageMaxMessage.classList.remove("hide-images-max");
            } else {
              imageMaxMessage.classList.add("hide-images-max");
            }
          };          

                 

      const renderImages = () => {
         let imagesToDisplay = JSON.parse(localStorage.getItem("images"))

         // remove feature i
             if(imagesToDisplay.length===0){
             
                feature.value = ""
             }

        // show max image msg
          showMaxImgMessage(imagesToDisplay)
       ////////////


          imageSection.innerHTML = ""
          let counter = 1

        if (imagesToDisplay) {
  
            imagesToDisplay.map(imgObj => {
              imageSection.innerHTML += `
          <label class='form__image-section__selection' for='image${counter}'>
          <div class="product-img">
          <input id='image${counter}' class='form__image-section__selection__radio' type='checkbox' name='image${counter}' checked value='${imgObj['img']}' >
          <img class='form__image-section__selection__image thumbnails' src='../media/product_images/${imgObj['img']}' data-imgname='${imgObj['img']}'class='thumbnails'>
          
          </label>      
          <button data-name='${imgObj['img']}' "type="button" class="remove-image">x</button>
          </div>
          `
              counter++
            })
        }


        

    }
    renderImages()
    document.addEventListener("click", (e) => {
      if (e.target.className == "remove-image") {
        e.target.dataset.name == feature.value ? feature.value = "" : null
        let imagesFromLocalStorage = JSON.parse(localStorage.getItem("images"))
        images = imagesFromLocalStorage.filter((el)=> {
          return el.img !== e.target.dataset.name
        })
          localStorage.setItem("images", JSON.stringify(images));
        }
        renderImages()
    });



      document.addEventListener('click', e => {
 
      if(e.target.classList.contains('form__image-section__selection__image')){ 
        feature.value = e.target.dataset.imgname     
        e.target.classList.add('featured-image')

        const featureImg = document.querySelectorAll('.form__image-section__selection__image')
        featureImg.forEach(img => {
          img.classList.remove('featured-image')
          img.dataset.imgname === feature.value ? img.classList.add('featured-image') : null
        })

        

      }


    })


    let imagesToDisplay = JSON.parse(localStorage.getItem("images"))


    </script>
    <?php

?>
      </div>
    </div>
    <div id="errorDiv">
      <?php

if (!isset($_GET['formerror'])) {

} else {
    $errorCheck = $_GET['formerror'];
    if ($errorCheck == 'duplicate') {

        echo "<p class='errormsg'>You already have a product called <strong>$title</strong>.</p>";

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
    <button class="button add-product-btn" type="submit">Add Product</button>
  </form>
</section>
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
</script>
<?php
require_once './assets/foot.php';
?>