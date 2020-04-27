// Check localStorage and declear elements
function setUp() {
  const tbodyEl = document.querySelector(".confirmtable__tbody");

  if (typeof Storage !== "undefined") {
    let productsObj = JSON.parse(localStorage.cart);
    let totalPrice = JSON.parse(localStorage.total);
    let customerInfo = JSON.parse(localStorage.customer);
    let shippingfee = shippingFeeCheck(totalPrice, customerInfo);
    let productsTable = showConfirmationTable(productsObj);
    console.log(customerInfo);
    // Fill elements (Price info)
    tbodyEl.innerHTML = productsTable;
    document.querySelector(".confirmpage__productprice").innerHTML =
      totalPrice + " SEK";
    document.querySelector(".confirmpage__shippingfee").innerHTML =
      shippingfee + " SEK";
    document.querySelector(".confirmpage__totalprice").innerHTML =
      totalPrice + shippingfee + " SEK";

    // Fill element (Customer)
    document.querySelector(
      ".confirmpage__shipping__customer"
    ).innerHTML += customer(customerInfo);

    // Fill element (Payment method)
    document.querySelector(
      ".confirmpage__shipping__payment"
    ).innerHTML += `Your invoice will soon be<br> delivered to you, by your choosen invoice method`;
  }
}

// Get information about product and store it in a variable
function showConfirmationTable(productsObj) {
  let products = Object.values(productsObj);

  let productTable = "";

  for (let i = 0; i < products.length; i++) {
    productTable += `<tr><td class="confirmtable__tbody__productname">${products[i].name}</td><td>${products[i].quantity}</td><td>${products[i].price} SEK</td></tr>`;
  }

  return productTable;
}

// function localStorageCustomer() {
//   const customer = {
//     firstname: "Elin",
//     lastname: "Boström",
//     email: "ebostrom@live.se",
//     mobile: "0739257636",
//     address: "Gästrikegatan 11",
//     postal: "11362",
//     city: "Oslo",
//     invoice: "Email"
//   };

//   localStorage.setItem("customer", JSON.stringify(customer));
// }

function shippingFeeCheck(totalPrice, customerInfo) {
  let shippingFee =
    totalPrice > 500 || customerInfo.city == "Stockholm" ? 0 : 29;

  return shippingFee;
}

function customer(customerInfo) {
  return `<p>${customerInfo.firstname} ${customerInfo.lastname}</p>
  <p>${customerInfo.address}</p><p>${customerInfo.postal} ${customerInfo.city}</p>
  <br><p>${customerInfo.mobile}</p><p>${customerInfo.email}</p>`;
  localStorage.clear();
}

function checkPayMethod(customerInfo) {
  // return customerInfo.invoice == "Email" ? "email" : "doorstep";
}

setUp();
