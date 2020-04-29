<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://kit.fontawesome.com/10d18f6c7b.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <title>G03 - Webshop</title>
</head>

<body>
  <header class="header">
    <nav class="fixed">
      <div class="header__logo"><a href="index.php"> <img src="./media/images/logo.png" width="40" height="40" /> </a>
      </div>

      <ul class="header__nav">
        <div class="header__nav__container">
          <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home"
              href="index.php">Home</a>
          </li>

          <li class="header__nav__item"><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
              href="">Contact</a></li>
        </div>

        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__cart"><i
              class="fas fa-shopping-cart"></i></a></li>
      </ul>

      <div class="header__burger">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
    <section>
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
              href="">Contact</a></li>
        </div>

      </ul>
      <section class="cart hidden">
        <div class="cart__menu"></div>
        <section class="cart__product-wrapper"></section>
        <div class="cart__total-checkout"></div>
      </section>
      <h1>Welcoming text</h1>
      <p>Describing text about shop...</p>
    </section>
  </header>


  <script>
  //Toggle menu
  const burger = document.querySelector('.header__burger i');
  const nav = document.querySelector('.toggle_menu__container');

  function toggleNav() {
    burger.classList.toggle('fa-bars');
    burger.classList.toggle('fa-times');
    nav.classList.toggle('toggle_menu__active');
  }

  burger.addEventListener('click', function() {
    toggleNav();

  });

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
  <main class="main-checkout">
    <h1>Checkout</h1>
    <h3>You are about to buy these products</h3>

    <section id="pTable-section" class="order-summary">


    </section>


    <section class="customer">
      <form action="./confirm_page.php" id="confirm-order" class="customer__form" method="POST">
        <div class="customer__form__information">
          <h2>Customer Information</h2>
          <div class="customer__form__information__name">
            <div class="label-input">
              <label for="firstname">First name:</label>
              <input name="firstname" class="customer-info" id="firstname" type="text"></input>
            </div>
            <div class="label-input">
              <label for="lastname">Last name:</label>
              <input name="lastname" class="customer-info" id="lastname" type="text"></input>
            </div>
          </div>
          <div class="customer__form__information__mailbile">
            <div class="label-input">
              <label for="email">Email:</label>
              <input name="email" class="customer-info" id="email" type="text"></input>
            </div>
            <div class="label-input">
              <label for="mobile">Mobile:</label>
              <input name="mobile" class="customer-info" id="mobile" type="text"></input>
            </div>
          </div>
          <div class="customer__form__information__street">
            <div class="label-input">
              <label for="street">Street:</label>
              <input name="street" class="customer-info" id="street" type="text"></input>
            </div>
          </div>
          <div>

          </div>
          <div class="customer__form__information__cityPost">
            <div class="label-input postalcode">
              <label for="postal">Postal:</label>
              <input name="postal" class="customer-info" id="postal" type="text"></input>
            </div>
            <div class="label-input city">
              <label for="city">City:</label>
              <input name="city" class="customer-info" id="city" type="text"></input>
            </div>
          </div>
        </div>
        <div class="customer__form__payment customer__form__information">
          <h2>Payment Information</h2>
          <div class="customer__form__payment__shipping">
            <p>Shipping Fee</p>
            <p class="sum">SEK</p>
          </div>
          <div class="customer__form__payment__total">
            <p>Total price</p>
            <p class="sum">SEK</p>
          </div>
          <div class="customer__form__payment__invoice">
            <p>Invoice</p>
            <label for="email-invoice"><input name="email-invoice" class="customer-info" type="checkbox"
                id="email-invoice" name="email-invoice" value="checkbox">Email
            </label>
            <label for="adress-invoice"><input name="adress-invoice" class="customer-info" type="checkbox"
                id="adress-invoice" name="adress-invoice" value="checkbox">Adress
            </label>
          </div>
          <input id="cart-input" type="hidden" name="cart" type="text"></input>
          <input id="total-input" type="hidden" name="total" type="text"></input>
        </div>

        <div class="customer__form__submit">
          <button id="order-confirmation-submit">Confirm order</button>
        </div>
      </form>
    </section>

  </main>






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