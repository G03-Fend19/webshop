<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://kit.fontawesome.com/10d18f6c7b.js"crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <title>Document</title>
</head>
<body>



<header class="header">
  <div class="header__logo">Logo</div>
  <nav>
    <ul class="header__nav">
      <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__home" href="">Home</a></li>
      <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__contact" href=""><i
            class="fas fa-phone"></i></a></li>
      <li class="header__nav__item"><input class="header__nav__item__searchbar hidden" placeholder="search..."
          type="text"></input> </li>
      <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__search"><i
            class="fas fa-search"></i></a></li>
      <li class="header__nav__item"><a class="header__nav__item__a header__nav__item__cart" href=""><i
            class="fas fa-shopping-cart"></i></a></li>
    </ul>
  </nav>
</header>



<script>
    (()=>{
       const searchBar = document.querySelector('.header__nav__item__searchbar');
       const searchButton = document.querySelector('.header__nav__item__search');
       const searchIcon = document.querySelector('.fa-search')


       searchButton.addEventListener('click', () => {
         searchIcon.classList.toggle("fa-times")
         searchBar.classList.toggle("hidden");
       })

    })();
</script>