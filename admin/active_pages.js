// active = () => {
//   const activeLinkOrders = document.querySelectorAll(".orders__link");
//   let url = window.location.href;
//   let activeOrder = "active";
//   let completedOrder = "completed";
//   let arrowIcon = "<i class='fas fa-chevron-right'></i>";

//   for (let i = 0; i < activeLinkOrders.length; i++) {
//     if (url.includes(activeOrder)) {
//       activeLinkOrders[1].classList.toggle("orders__link-active");
//       activeLinkOrders[1].parentElement.innerHTML += arrowIcon;
//     }

//     if (url.includes(completedOrder)) {
//       activeLinkOrders[2].classList.toggle("orders__link-active");
//       activeLinkOrders[2].parentElement.innerHTML += arrowIcon;
//     }
//   }
// };

// active();

function allActive() {
  const headline = document.querySelector(".headline__php");
  const menu = document.querySelector(".name__link-products");
  const manage = document.querySelector(".name__link-manage");
  const dash = document.querySelector(".name__link-dashboard");
  const newProd = document.querySelector(
    ".aside__nav__ul__li__title__addProduct"
  );
  const newCat = document.querySelector(
    ".aside__nav__ul__li__title__addCategory"
  );
  const allOrders = document.querySelector(".orders__link-showAll");
  const activeOrders = document.querySelector(".orders__link-activeOrders");
  const complOrders = document.querySelector(".orders__link-completed");
  const arrow = "<i class='fas fa-chevron-right'></i>";

  switch (headline.innerHTML) {
    case "All products":
      menu.classList.add("orders__link-active");
      menu.parentElement.innerHTML += arrow;
      break;

    case "Categories":
      manage.classList.add("orders__link-active");
      manage.parentElement.innerHTML += arrow;
      newCat.classList.add("orders__link-active");
      newCat.parentElement.innerHTML += arrow;
      break;

    case "Dashboard":
      dash.classList.add("orders__link-active");
      dash.parentElement.innerHTML += arrow;
      break;

    case "Add new product":
      newProd.classList.add("orders__link-active");
      newProd.parentElement.innerHTML += arrow;
      break;

    case "Orders":
      allOrders.classList.add("orders__link-active");
      allOrders.parentElement.innerHTML += arrow;
      break;

    case "Active orders":
      activeOrders.classList.add("orders__link-active");
      activeOrders.parentElement.innerHTML += arrow;
      break;

    case "Completed orders":
      complOrders.classList.add("orders__link-active");
      complOrders.parentElement.innerHTML += arrow;
      break;
  }
}

allActive();
