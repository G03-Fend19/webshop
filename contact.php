<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://kit.fontawesome.com/10d18f6c7b.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <title>Clothera - Contact</title>
</head>

<body>
  <header class="contact">
    <nav class="fixed">
      <div class="contact__logo"><a href="index.php"> <img src="./media/images/logo_white.png" width="40" height="40" />
        </a>
      </div>

      <ul class="contact__nav">
        <div class="contact__nav__container">
          <li class="contact__nav__item"><a class="contact__nav__item__a contact__nav__item__home"
              href="index.php">Home</a>
          </li>

          <li class="contact__nav__item"><a id="contact-desktop"
              class="contact__nav__item__a contact__nav__item__contact2" href="">Contact</a></li>
        </div>

        <li class="contact__nav__item"><a class="contact__nav__item__a contact__nav__item__cart"><i
              class="fas fa-shopping-cart"></i></a></li>
      </ul>

      <div class="contact__burger">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
    <section class="contact__hero"
      style="background-image: url(./media/images/contact-background.jpeg); background-size:cover;">
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
      <h1>Meet the team</h1>
      <p>What can we do for you?</p>
    </section>
  </header>
  <main class="contact__wrapper">
    <section class="meetTheTeam">
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/evelina.png" alt="Evelina"></div>
        <div class="meetTheTeam__info">
          <h2>Evelina<br>Björkman</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/elias.png" alt="Elias"></div>
        <div class="meetTheTeam__info">
          <h2>Elias<br>Högelin</h2><strong class="tester">Qualified tester</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/lovisa.png" alt="Lovisa"></div>
        <div class="meetTheTeam__info">
          <h2>Lovisa<br>Engström</h2><strong class="tester">Qualified tester</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/matilda.png" alt="Matilda"></div>
        <div class="meetTheTeam__info">
          <h2>Matilda<br>Söderblom</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/hellberg.png" alt="Andreas"></div>
        <div class="meetTheTeam__info">
          <h2>Andreas<br>Hellberg</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/lova.png" alt="Lova"></div>
        <div class="meetTheTeam__info">
          <h2>Lova<br>Duvnäs</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/henning.png" alt="Andreas"></div>
        <div class="meetTheTeam__info">
          <h2>Andreas<br>Henningson</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/elin.png" alt="Elin"></div>
        <div class="meetTheTeam__info">
          <h2>Elin<br>Boström</h2><strong>Frontend Developer</strong>
        </div>
      </article>
      <article class="meetTheTeam__card">
        <div class="meetTheTeam__photo"><img src="./media/images/erik.png" alt="Erik"></div>
        <div class="meetTheTeam__info">
          <h2>Erik<br>Lagnefeldt</h2><strong>Frontend Developer</strong>
        </div>
      </article>
    </section>

  </main>


  <script>
  //Toggle menu
  const burger = document.querySelector('.contact__burger i');
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