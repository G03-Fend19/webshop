<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <title>Clothera - Startpage</title>
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
        <h1>Spring vibes</h1>
        <p class="desc__hero__text">More new amazing products in stock...</p>
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

      <div id="noMoreInStockModal" class="modal">
        <div class="modal__content">
          <div class="modal__content__header">
            <span class="cancel-btn  close">&times;</span>
            <h2>Alert</h2>
          </div>
          <div class="modal__content__body">
            <p>No more in stock</p>
          </div>
          <div class="modal__content__footer">
            <button class="cancel-btn">Cancel</button>
          </div>
        </div>
      </div>

    </section>

  </header>

  <main id="main">