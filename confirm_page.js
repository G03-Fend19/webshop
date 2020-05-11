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

    console.log(customerInfo.emailInvoice);

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
    ).innerHTML += checkPayMethod(customerInfo);
  }
}

// Get information about product and store it in a variable
function showConfirmationTable(productsObj) {
  let products = Object.values(productsObj);

  let productTable = "";

  for (let i = 0; i < products.length; i++) {
    priceDisplay = "";
    if (products[i].discount === 1) {
      // console.log(products[i].discount);
      priceDisplay = `<p class='price'> ${
        products[i].quantity * products[i].price
      } SEK</p>`;
    } else {
      // console.log("discount");
      priceDisplay = `<p class='price__line-through'> ${
        products[i].quantity * products[i].price
      } SEK</p>
                            <p class='price__discount'> ${Math.ceil(
                              products[i].quantity *
                                (products[i].price * products[i].discount)
                            )} SEK</p>`;
    }
    productTable += `<tr><td class="confirmtable__tbody__productname">${products[i].name}</td><td>${products[i].quantity}</td><td>${priceDisplay}</td></tr>`;
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
  let cityInfo = customerInfo.city.toLowerCase();
  let shippingFee = totalPrice > 500 || cityInfo.city == "stockholm" ? 0 : 29;

  return shippingFee;
}

function customer(customerInfo) {
  localStorage.clear();
  return `<p>${customerInfo.firstname} ${customerInfo.lastname}</p>
  <p>${customerInfo.street}</p><p>${customerInfo.postal} ${customerInfo.city}</p>
  <br><p>${customerInfo.mobile}</p><p>${customerInfo.email}</p>`;
}

function checkPayMethod(customerInfo) {
  let invoiceAnswer = "";

  if (customerInfo.emailInvoice == true && customerInfo.adressInvoice == true) {
    invoiceAnswer =
      "Your invoice will soon be<br> delivered to you by email and letter";
  } else if (customerInfo.emailInvoice == true) {
    invoiceAnswer = "Your invoice will soon be<br> delivered to you by email";
  } else if (customerInfo.adressInvoice == true) {
    invoiceAnswer = "Your invoice will soon be<br> delivered to you by letter";
  } else {
    invoiceAnswer =
      "You didn't choose invoice method so it will be delivered to your email shortly.";
  }

  return invoiceAnswer;
}

setUp();
