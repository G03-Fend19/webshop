const productSection = document.querySelector("#pTable-section");
const shippingP = document.getElementById("shipping_fee");
const totalSumP = document.getElementById("total_sum");
const cityInput = document.getElementById("city");
let fee;

const calcTotalCheckout = () => {
  let order = JSON.parse(localStorage.getItem("cart"));

  !order ? (order = {}) : null;
  total = Object.keys(order).reduce((acc, cur) => {
    return (
      acc +
      Math.ceil(order[cur].price * order[cur].discount * order[cur].quantity)
    );
  }, 0);
  localStorage.setItem("total", JSON.stringify(total));

  return `${total}`;
};

const calcTotalWithShipping = () => {
  let totalSum = calcTotalCheckout();
  totalSum = parseInt(totalSum);
  const city = cityInput.value;
  if (totalSum > 500) {
    fee = 0;
  } else if (city.toLowerCase() == "stockholm") {
    fee = 0;
  } else {
    fee = 29;
  }
  shippingP.innerHTML = `${fee} SEK`;
  totalSumP.innerHTML = `${totalSum + fee} SEK`;
  localStorage.setItem("shipping", JSON.stringify(fee));
};

const productsInCheckout = () => {
  let order = JSON.parse(localStorage.cart);
  let total = 0;
  Object.keys(order).forEach((el) => {
    total += order[el].quantity;
  });
  return total;
};

const renderOrderSummary = () => {
  let order = JSON.parse(localStorage.getItem("cart"));

  !order ? (order = {}) : null;

  if (Object.keys(order).length != 0 && order.constructor === Object) {
    let productTable = `<table class="order-summary__table">
                        <thead>
                          <tr>
                            <th></th>
                            <th>Product name</th>
                            <th>Quantity</th>
                            <th class="price-heading">Price</th>
                          </tr>
                        </thead>
                        <tbody>`;

    productTable += Object.keys(order)
      .map((product) => {
        priceDisplay = "";
        if (order[product].discount === 0.9) {
          priceDisplay = `<p class='price'> ${
            order[product].quantity * order[product].price
          } SEK</p>`;

          priceDisplay = `<p class='price__line-through'> ${
            order[product].quantity * order[product].price
          } SEK</p>
                            <p class='price__discount'> ${Math.ceil(
                              order[product].quantity *
                                (order[product].price * order[product].discount)
                            )} SEK</p>`;
        } else {
          priceDisplay = `<p class='price'> ${
            order[product].quantity * order[product].price
          } SEK</p>`;
        }
        return `
          <tr data-name="${order[product].name}">
            <td>
              <img class="order-summary__table__img" src="./media/product_images/${order[product].img}" alt="Product image">
            </td>
            <td>${order[product].name}</td>
            <td class="order-summary__table__qty">${order[product].quantity} st</td>
            <td class="order-summary__table__price">${priceDisplay}</td>
          </tr>
    `;
      })
      .join("");

    productTable += `</tbody></table>`;

    productSection.innerHTML = productTable;

    let totalDiv = document.createElement("div");
    totalDiv.classList.add("order-summary__totalcontainer");

    let totalSum = `<p class="order-summary__totalcontainer__price"><strong>Price</strong> ${calcTotalCheckout()} SEK</p>`;

    totalDiv.innerHTML = totalSum;

    productSection.append(totalDiv);

    //${productsInCheckout()} st
  } else {
    productSection.innerHTML = "<h3>Your cart is empty.</h3>";
  }
};

renderOrderSummary();
calcTotalWithShipping();

cityInput.addEventListener("input", function () {
  calcTotalWithShipping();
});
