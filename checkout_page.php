<?php
session_start();
$errorModal = "";
$errorMsg = "";
?>
<script>let error = false;</script>
<?php
if(isset($_GET['error'])) {
  $errorModal .= "<div id='checkoutErrorModal' class='modal'>
  <div class='modal__content'>
    <div class='modal__content__header'>
      <span class='close'>&times;</span>
      <h2>Error</h2> 
    </div>
    <div class='modal__content__body'>";

    if ($_GET['error'] == "mail") {
      ?><script>error = true</script><?php
      $errorMsg .= "<p>Email already registered on a different name. Please check spelling or use different mail</p>";
    }
    if ($_GET['error'] == "empty") {
      ?><script>error = true</script><?php
      $errorMsg .= "<p>No products in cart</p>";
    }
    if ($_GET['error'] == "out_of_stock") {
      ?><script>error = true</script><?php
    $soldOutProducts = $_SESSION['sold_out_products'];
    ?>
    <script>let soldOutProductsFromPHP = <?php echo json_encode($soldOutProducts);?>;</script>
    <?php
    $errorMsg .="<p>Unfortunately these products sold out before you completed your order and will be removed:</p><ul>";
    foreach ($soldOutProducts as $key => $product) {
      $productName = $product['SoldOutProductName'];
      $productId = $product['SoldOutProductId'];
    
      $errorMsg .= "<li>$productName</li>";
    }
    $errorMsg .= "</ul>";
    unset($_SESSION['sold_out_products']);
  }
  $errorModal .= $errorMsg;
  $errorModal .= "</div>
  <div class='modal__content__footer'>
  <button id='cancel' class='cancel-btn'>Close</button>  
  
  </div>
  </div>
  
  </div>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <title>Clothera - Checkout page</title>
</head>

<body>
  <header class="header">
    <nav class="fixed">
      <div class="header__logo"><a href="index.php"> <img src="./media/images/logo_white.png" width="40" height="40" />
        </a>
      </div>

      <ul class="header__nav">
        <div class="header__nav__container">
          <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home"
              href="index.php">Home</a>
          </li>

          <li class="header__nav__item"><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
              href="contact.php">Contact</a></li>
        </div>

        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__cart"><i
              class="fas fa-shopping-cart"></i>
            <span class="cart_qty_show"></span>
          </a></li>
      </ul>

      <div class="header__burger">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
    <section class="header__hero">
      <form class="searchform" name="search_form" action="search.php#main" onsubmit="return validateSearchForm()"
        method="GET">
        <input class="searchform__searchbar " placeholder="Search..." type="text" name="search">
        <button class="searchform__searchBtn search "> <i class="fa fa-search"></i></button>
      </form>

      <ul class="toggle_menu">
        <div class="toggle_menu__container">
          <li class="toggle_menu__item"><a class="toggle_menu__item__a toggle_menu__item__home"
              href="index.php">Home</a>
          </li>
          <li class="toggle_menu__item"><a id="contact-desktop" class="toggle_menu__item__a toggle_menu__item__contact2"
              href="contact.php">Contact</a></li>
        </div>

      </ul>
      <section class="cart hidden">
        <div class="cart__menu"></div>
        <section class="cart__product-wrapper"></section>
        <div class="cart__total-checkout"></div>

      </section>
      <div class="header__hero__text">
        <h1>Lucky you!</h1>
        <p class="desc__hero__text">Soon the products is yours!</p>
      </div>

      <div id="myModal" class="modal">
        <div class="modal__content">
          <div class="modal__content__header">
            <span class="close">&times;</span>
            <h2>Confirmation</h2>
          </div>
          <div class="modal__content__body">
            <p>Are you sure you would like to remove all items from the shopping cart?</p>
          </div>
          <div class="modal__content__footer">
            <button class="cancel-btn">Cancel</button> <button class="clear-cart">
              Clear Cart
            </button>
          </div>
        </div>

      </div>
      <?php echo $errorModal?>

    </section>

  </header>


  <script>
  //Toggle menu
  // const burger = document.querySelector('.header__burger i');
  // const nav = document.querySelector('.toggle_menu__container');

  // function toggleNav() {
  //   burger.classList.toggle('fa-bars');
  //   burger.classList.toggle('fa-times');
  //   nav.classList.toggle('toggle_menu__active');
  // }

  // burger.addEventListener('click', function() {
  //   toggleNav();

  // });

  /* if (nav.classList.contains('toggle_menu__active')) {
  document.addEventListener('click', function(event) {
          let isClickInside = nav.contains(event.target);
          if (isClickInside) {
            console.log('You clicked inside')
          }
         else {
            console.log('You clicked outside')
            nav.classList.remove("toggle_menu__active");

          }
      });
    }
       */
  </script>
  <main id="main-checkout" class="main-checkout">
    <h1>Checkout</h1>
    <h3>You are about to buy these products</h3>

    <section id="pTable-section" class="order-summary">


    </section>
    <script>
    const localStorageCustomer = JSON.parse(localStorage.getItem("customer"))
    </script>

    <section class="customer">
      <form action="./confirm_page.php" id="confirm-order" class="customer__form" method="POST">
        <div class="customer__form__information">
          <h2>Customer Information</h2>
          <div class="customer__form__information__name">
            <div class="label-input">
              <label for="firstname">First name:</label>
              <input name="firstname" class="customer-info" id="firstname" type="text" maxlength="50" required>
            </div>
            <div class="label-input">
              <label for="lastname">Last name:</label>
              <input name="lastname" class="customer-info" id="lastname" type="text" maxlength="100" required>
            </div>
          </div>
          <div class="customer__form__information__mailbile">
            <div class="label-input">
              <label for="email">Email:</label>
              <input name="email" class="customer-info" id="email" type="email" maxlength="50" required>
            </div>
            <div class="label-input">
              <label for="mobile">Mobile:</label>
              <input name="mobile" class="customer-info" id="mobile" type="text" maxlength="30" required>
            </div>
          </div>
          <div class="customer__form__information__street">
            <div class="label-input">
              <label for="street">Street:</label>
              <input name="street" class="customer-info" id="street" type="text" maxlength="50" required>
            </div>
          </div>
          <div>

          </div>

          <div class="customer__form__information__cityPost">
            <div class="label-input postalcode">
              <label for="postal">Postal:</label>
              <input name="postal" class="customer-info" id="postal" type="text"  maxlength="30" required>
            </div>
            <div class="label-input city">
              <label for="city">City:</label>
              <input name="city" class="customer-info" id="city" type="text"  maxlength="50" required>
            </div>

          </div>
        </div>
        <div class="customer__form__payment customer__form__information">
          <h2>Payment Information</h2>
          <div class="customer__form__payment__shipping">
            <p>Shipping Fee</p>
            <p id='shipping_fee' class="sum"></p>
          </div>
          <div class="customer__form__payment__total">
            <p>Total price</p>
            <p id='total_sum' class="sum"></p>
          </div>
          <div class="customer__form__payment__invoice">
            <p>Invoice: </p>

            <label for="emailInvoice"><input name="emailInvoice" class="customer-info" type="checkbox"
                id="emailInvoice" name="emailInvoice" value="checkbox" checked>Email
            </label>

            <label for="adressInvoice"><input name="adressInvoice" class="customer-info" type="checkbox"
                id="adressInvoice" name="adressInvoice" value="checkbox"> Address
            </label>

          </div>
          <input id="cart-input" type="hidden" name="cart">
          <input id="total-input" type="hidden" name="total">
        </div>

        <div class="customer__form__submit">
          <button id="order-confirmation-submit">Confirm order</button>
        </div>
      </form>
    </section>


    <script>
    const customerFromLocalStorage = JSON.parse(localStorage.getItem('customer'))
    const customerInformationFields = document.querySelectorAll(".customer-info");
    const carten = JSON.parse(localStorage.getItem('cart'));

    if (Object.keys(carten).length === 0 && carten.constructor === Object) {
      const confirmForm = document.getElementById('confirm-order');
      confirmForm.classList.add('hidden');
    }

    if (customerFromLocalStorage == null || customerInformationFields == null) {} else {
      const customerInformationArray = Array.prototype.slice.call(
        customerInformationFields
      );
      customerKeys = Object.keys(customerFromLocalStorage)

      if (customerFromLocalStorage) {
        customerKeys.forEach(key => {
          customerInformationArray.filter(field => {
            field.name === key ? field.value = customerFromLocalStorage[key] : null
          })
        })
      }
    }

    const confirmOrderBtn = document.getElementById('order-confirmation-submit');

    confirmOrderBtn.addEventListener('click', function(e) {

      const firstname = document.getElementById('firstname').value;
      const lastname = document.getElementById('lastname').value;
      const email = document.getElementById('email').value;
      const mobile = document.getElementById('mobile').value;
      const street = document.getElementById('street').value;
      const postal = document.getElementById('postal').value;
      const city = document.getElementById('city').value;

      validateField(firstname, 'firstname', e, 50);
      validateField(lastname, 'lastname', e, 100);
      validateField(city, 'city', e, 50);

      validateEmail(email);

      validatePostalMobile(mobile, 'mobile', event);
      validatePostalMobile(postal, 'postal', event);

      validateStreet(street);

      if (!validLength(mobile, 30)) {
        alert('Your number has to be between 2 and 30 numbers.');
        event.preventDefault();
      };

      if (!validLength(postal, 30)) {
        alert('Your number has to be between 2 and 30 numbers.');
        event.preventDefault();
      };


    })

    function validLength(value, maxLength){

    if (value.length >= 2 && value.length <= maxLength) {
        return true;
    } else {
        return false;
    }

    }

    function validateField(value, fieldName, event, maxLength) {
      if (!onlyValidCharacters(value)) {
        alert('You have unvalid chars in your ' + fieldName + '.');
        event.preventDefault();
      }

      if (!validLength(value, maxLength)) {
        alert('The ' + fieldName + 'field has to be between 2 and ' + maxLength + ' characters.');
        event.preventDefault();
      };
    }

    function onlyValidCharacters(inputtxt) {

      const validLetters = /^[a-zA-ZäöåÄÖÅ\s-é]+$/;

      if(inputtxt.match(validLetters)) {
        return true;
      } else {
        return false;
      }

    }

    function validateEmail(email) {
      const validFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if(!email.match(validFormat)) {
        alert('This is not a valid email-address.');
        event.preventDefault();
      }

      if (!validLength(email, 50)) {
        alert('Your email has to be between 2 and 50 letters.');
        event.preventDefault();
      }
    }

    function validatePostalMobile(value, fieldName, event) {
      if (!onlyValidNumbers(value)) {
        alert('You have unvalid characters in your ' + fieldName + '.');
        event.preventDefault();
      }
    }

    function onlyValidNumbers(inputnum) {
      const validNumbers = /^[0-9\s-]+$/;

      if(inputnum.match(validNumbers)) {
        return true;
      } else {
        return false;
      }
    }

    function validateStreet(street) {

      const validChars = /^[0-9a-zA-ZäöåÄÖÅ\s-é.]+$/;

      if(!street.match(validChars)) {
        alert('This is not a valid street name.');
        event.preventDefault();
      }

      if (!validLength(street, 50)) {
        alert('Your street address has to be between 2 and 50 letters.');
        event.preventDefault();
      }
    }
    if (error == true) {
      const modal = document.getElementById("checkoutErrorModal");
      const span = document.getElementsByClassName("close")[0];
      //const deleteIcon = document.getElementById("delete-product");
       modal.style.display = "block";
       //close the modal
      span.onclick = function() {
        modal.style.display = "none";
      };
        // clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        };
        document.addEventListener("click", e => {
          if (e.target.className == "cancel-btn") {
            modal.style.display = "none";
          }
        });
      }

    </script>

    <script>
    // allProductsObj = localStorage.getItem('cart');
    // console.log(allProductsObj)
    document.getElementById('cart-input').value = localStorage.getItem('cart');
    document.getElementById('total-input').value = localStorage.getItem('total');
    </script>
    <script src="./checkout.js"></script>

    <script src="./order-confirmation.js"></script>

    <?php

require_once 'assets/foot.php';
?>