<?php 


?>

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
        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home" href="index.php">Home</a>
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
    <form class="searchform" name="search_form" action="search.php#main"
            onsubmit="return validateSearchForm()" method="GET">
            <input class="searchform__searchbar " placeholder="Search..." type="text" name="search">
            <button class="searchform__searchBtn search "> <i class="fa fa-search"></i></button>
          </form>

          <ul class="toggle_menu">
        <div class="toggle_menu__container">
        <li class="toggle_menu__item"><a class="toggle_menu__item__a toggle_menu__item__home" href="index.php">Home</a>
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
 
  <main class="confirmpage">
    <div class="confirmpage__container">
      <section class="confirmpage__order">
        <h1>Thank you for your order!</h1>

        <table class="confirmtable">
          <thead class="confirmtable__thead">
            <tr>
              <th class="confirmtable__thead__productname">Product name</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody class="confirmtable__tbody">
            <!-- Här ska kod från localStorage in med JS -->
          </tbody>
        </table>

        <section class="confirmpage__price">
          <div class="confirmpage__price__products">
            <strong>Price</strong>
            <p class="confirmpage__productprice"></p>
          </div>
          <div class="confirmpage__price__shipping">
            <strong>Shipping fee</strong>
            <p class="confirmpage__shippingfee"></p>
          </div>
          <div class="confirmpage__price__total">
            <strong>Total price</strong>
            <p class="confirmpage__totalprice">2 099 SEK</p>
          </div>
        </section>
      </section>

      <section class="confirmpage__information">
        <h2>It will be shipped to...</h2>

        <div class="confirmpage__shipping">
          <div class="confirmpage__shipping__customer">
            <h3>Customer</h3>
          </div>

          <div class="confirmpage__shipping__payment">
            <h3>Payment method</h3>
          </div>
        </div>
      </section>
      <a class="confirmpage__shopmorebtn" href="index.php"><button>Shop more</button></a>

    </div>
  </main>
  <script src="confirm_page.js"></script>

  <?php
    

require_once './assets/foot.php';