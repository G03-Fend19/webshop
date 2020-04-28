
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

    <section class="cart">
      <div class="cart__menu"></div>
      <section class="cart__product-wrapper"></section>
      <div class="cart__total-checkout"></div>
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
  <main class="main-checkout">
    <h1>Checkout</h1>
    <h3>You are about to buy these products</h3>

    <section id="pTable-section" class="order-summary">


    </section>


    <section class="customer"> 
      <form  id="confirm-order"  class="customer__form" method="POST">
        <div class="customer__form__information">
          <h2>Customer Information</h2>
          <div class="customer__form__information__name">
            <div class="label-input">
              <label for="fname">First name:</label>
              <input name="firstname" class="customer-info"id="firstname"type="text"></input>
            </div>
            <div class="label-input">
              <label for="lname">Last name:</label> 
              <input name="lastname" class="customer-info"id="lastname"type="text"></input> 
            </div>
          </div>
          <div class="customer__form__information__mailbile">
            <div class="label-input">
              <label for="email">Email:</label> 
              <input name="email" class="customer-info"id="email"type="text"></input>   
            </div>  
            <div class="label-input">
              <label for="mobile">Mobile:</label> 
              <input name="mobile" class="customer-info"id="mobile"type="text"></input>  
            </div>                      
          </div>
          <div class="customer__form__information__street">
            <div class="label-input">
              <label for="adress">Street:</label> 
              <input name="street"class="customer-info"id="street"type="text"></input>  
            </div>            
          </div>
          <div>

          </div>
          <div class="customer__form__information__cityPost"> 
            <div class="label-input">
              <label for="postal">Postal:</label> 
              <input name="postal"class="customer-info"id="postal"type="text"></input>  
            </div>      
             <div class="label-input">
               <label for="city">City:</label> 
               <input name="city"class="customer-info"id="city"type="text"></input>  
             </div>                 
          </div>
        </div>
        <div class="customer__form__payment customer__form__information">
            <h2>Payment Information</h2>
            <div class="customer__form__payment__shipping">
              <p>Shipping Fee</p> 
              <p>kronor</p>
            </div>
            <div class="customer__form__payment__total">
              <p>Total price</p> 
              <p>kronor</p>
            </div>
            <div class="customer__form__payment__invoice">
              <p>Invoice</p>
                <label for="email-invoice"> Email </label>
                <input  name="email-invoice"class="customer-info" type="checkbox" id="email-invoice" name="email-invoice" value="checkbox">
                <label for="adress-invoice"> Adress </label>
                <input  name="adress-invoice"class="customer-info" type="checkbox" id="adress-invoice" name="adress-invoice" value="checkbox">                
            </div>      
            <input id="cart-input" name="cart" type="text" ></input>                     
        </div>
        
        <div class="customer__form__submit">
          <button id="order-confirmation-submit">Confirm order</button>
        </div>
      </form>
    </section>

  </main>



  
  
  
  <script>
    document.getElementById('cart-input').value = JSON.stringify(localStorage.getItem('cart'));
  </script>
  <script src="./checkout.js"></script>

  <script src="./order-confirmation.js"></script>

  <?php

  
require_once 'assets/foot.php';
?>