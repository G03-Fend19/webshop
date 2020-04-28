<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://kit.fontawesome.com/10d18f6c7b.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <title>G03 - Contact</title>
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
    <section class="header__hero__contact">
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
      <section class="cart">
        <div class="cart__menu"></div>
        <section class="cart__product-wrapper"></section>
        <div class="cart__total-checkout"></div>
      </section>
      <h1>Say hello</h1>
      <p>What can we do for you?</p>
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
  </script>

  <?php

require_once "./assets/foot.php";