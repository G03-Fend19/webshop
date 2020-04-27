const productSection = document.querySelector("#pTable-section");
const calcTotalCheckout = () => {
  let order = JSON.parse(localStorage.cart);
  total = Object.keys(order).reduce((acc, cur) => {
    return acc + order[cur].price * order[cur].quantity;
  }, 0);
  localStorage.setItem("total", JSON.stringify(total));
  return `${total}`;
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
  let order = JSON.parse(localStorage.cart);

  console.log(order);

  if (Object.keys(order).length != 0 && order.constructor === Object) {
    let productTable = `<table class="order-summary__table">
                        <thead>
                          <tr>
                            <th></th>
                            <th></th>
                            <th>Product name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                          </tr>
                        </thead>
                        <tbody>`;

    productTable += Object.keys(order)
      .map((product) => {
        return `
          <tr data-name="${order[product].name}">
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
