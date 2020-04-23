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
      <div class="header__logo"><a href="index.php"> <img src=".\media\images\logo.png" width="40" height="40" /> </a>
      </div>
      <ul class="header__nav">
        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home" href="index.php">Home</a>
        </li>
        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__contact" href=""><i
              class="fas fa-phone"></i></a></li>
        <li class="header__nav__item"><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
            href="">Contact</a></li>
        <li class="header__nav__item">
          <form class="header__nav__item__searchform" name="search_form" action="search.php#main"
            onsubmit="return validateSearchForm()" method="GET">
            <input class="header__nav__item__searchbar hidden" placeholder="Search..." type="text" name="search">
            </input>
            <button class="header__nav__item__searchBtn search hidden">Search</button>
          </form>
        </li>
        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__search"><i
              class="fas fa-search"></i></a></li>
        <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__cart"><i
              class="fas fa-shopping-cart"></i></a></li>
      </ul>
    </nav>
    <section class="header__hero">
      <section class="cart">
        <div class="cart__menu"></div>
        <section class="cart__product-wrapper"></section>
        <div class="cart__total-checkout"></div>
      </section>
      <h1>Welcoming text</h1>
      <p>Describing text about shop...</p>
    </section>
  </header>
  <script>
  (() => {
    const searchBar = document.querySelector('.header__nav__item__searchbar');
    const searchButton = document.querySelector('.header__nav__item__search');
    const searchIcon = document.querySelector('.fa-search')
    const home = document.querySelector('.header__nav__item__home')
    const contact = document.querySelector('.header__nav__item__contact')
    const searchBtn = document.querySelector('.search')
    searchButton.addEventListener('click', () => {
      searchIcon.classList.toggle("fa-times")
      home.classList.toggle("hidden")
      searchBtn.classList.toggle("hidden");
      searchBar.classList.toggle("hidden");
    })
  })();
  </script>
  <main id="main">