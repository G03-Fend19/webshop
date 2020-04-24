// Check localStorage and declear elements
function setUp() {
  const tbodyEl = document.querySelector(".confirmtable__tbody");
  const customer = {
    firstname: "Elin",
    lastname: "Boström",
    email: "ebostrom@live.se",
    mobile: "0739257636"
  };

  const paymentMethod = {
    email: "yes",
    paper: "no"
  };

  localStorage.setItem("customer", JSON.stringify(customer));
  localStorage.setItem("payment", JSON.stringify(paymentMethod));

  if (typeof Storage !== "undefined") {
    let productsObj = JSON.parse(localStorage.cart);
    let totalPrice = JSON.parse(localStorage.total);

    let productsTable = showConfirmationTable(productsObj);
    tbodyEl.innerHTML = productsTable;
    document.querySelector(".confirm__totalprice").innerHTML = totalPrice + " SEK";
  } else {
    console.log("Nothing in localStorage");
  }
}

// Check localStorage and declear elements
setUp();

// Get information about product and store it in a variable
function showConfirmationTable(productsObj) {
  let products = Object.values(productsObj);

  let productTable = "";

  for (let i = 0; i < products.length; i++) {
    // console.log(products[i]);

    productTable += `<tr><td class="confirmtable__tbody__productname">${
    products[i].name}</td><td>${products[i].quantity}</td><td>${
    products[i].price} SEK</td></tr>`;
  }

  return productTable;
}
