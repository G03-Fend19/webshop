//Toggle menu
const burger = document.querySelector(".header__burger i");
const nav = document.querySelector(".toggle_menu__container");

function toggleNav() {
  burger.classList.toggle("fa-bars");
  burger.classList.toggle("fa-times");
  nav.classList.toggle("toggle_menu__active");
}

burger.addEventListener("click", function () {
  toggleNav();
});

//close menu if click outside
/* document.addEventListener('click', function(event) {
        let isClickInside = nav.contains(event.target);
        if (isClickInside) {
          console.log('You clicked inside')
        }
       else {
          console.log('You clicked outside')
          if (nav.classList.contains('toggle_menu__active')) {
         // nav.classList.remove("toggle_menu__active");
        }
        }
    });  */
