// Check localStorage and declear elements
function setUp() {
  const tbodyEl = document.querySelector(".confirmtable__tbody");

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

    productTable += `<tr><td>${products[i].name}</td><td>${
    products[i].quantity}</td><td>${products[i].price}</td></tr>`;
  }

  return productTable;
}
