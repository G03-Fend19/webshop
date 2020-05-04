const productSection = document.querySelector("#pTable-section");
const calcTotalCheckout = () => {
  console.log("running calc total")
  let order = JSON.parse(localStorage.cart);
  total = Object.keys(order).reduce((acc, cur) => {
    return acc + Math.ceil((order[cur].price * order[cur].discount) * order[cur].quantity);
  }, 0);
  localStorage.setItem("total", JSON.stringify(total));
  console.log(total)
  return `${total}`;
};

// const calcTotal = () => {
//   total = Object.keys(cart).reduce((acc, cur) => {
//     return acc + Math.ceil((cart[cur].price * cart[cur].discount) * cart[cur].quantity);
//   }, 0);
//   localStorage.setItem("total", JSON.stringify(total));
//   return `<div class="cart__total"><p>Total price</p> <p>${total} SEK</p></div>`;
// };

const productsInCheckout = () => {
  let order = JSON.parse(localStorage.cart);
  let total = 0;
  Object.keys(order).forEach((el) => {
    total += order[el].quantity;
  });
  return total;
};

const renderOrderSummary = () => {
  let order = JSON.parse(localStorage.cart);

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
        priceDisplay = ""
        if (order[product].discount === 1) {
          console.log(order[product].discount)
          priceDisplay = `<p class='price'> ${order[product].quantity * order[product].price} SEK</p>`
        } else {
          console.log("discount")
          priceDisplay = `<p class='price__line-through'> ${order[product].quantity * order[product].price} SEK</p>
                            <p class='price__discount'> ${Math.ceil(order[product].quantity * (order[product].price * order[product].discount))} SEK</p>`
        }
        return `
          <tr data-name="${order[product].name}">
            <td>
              <img class="order-summary__table__img" src="./media/product_images/${
          order[product].img
          }" alt="Product image">
            </td>
            <td>${order[product].name}</td>
            <td class="order-summary__table__qty">${
          order[product].quantity
          } st</td>
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

// old table
/*<tr data-name="${order[product].name}">
<td><button class="order-summary__table__btn"><i class="fas fa-trash-alt" data-id="delete-product"></i></button></td>
<td>
  <img class="order-summary__table__img" src="./media/product_images/${
    order[product].img
  }" alt="Product image">
</td>
<td>${order[product].name}</td>
<td class="order-summary__table__qty">
  <input class="order-summary__table__qty-input" type="number" min="0" value="${
    order[product].quantity
  }">st
  <button class="order-summary__table__btn"><i class="fas fa-minus-circle" data-id="qty-"></i></button>
  <button class="order-summary__table__btn"><i class="fas fa-plus-circle" data-id="qty+"></i></button>
</td>
<td class="order-summary__table__price">${
  order[product].quantity * order[product].price
} SEK</td>
</tr>*/
