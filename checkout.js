const productSection = document.querySelector("#pTable-section");

renderOrderSummary();

function renderOrderSummary() {
  let order = JSON.parse(localStorage.cart);

  console.log(order);

  if (Object.keys(order).length != 0 && order.constructor === Object) {
    let productTable = `<table class="order-summary">
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
            <td><button class="qty-btn"><i class="fas fa-trash-alt" data-id="delete-product"></i></button></td>
            <td>
              <img class="order-summary__img" src="./media/product_images/${
                order[product].img
              }" alt="Product image">
            </td>
            <td>${order[product].name}</td>
            <td>
              <input type="number" value="${order[product].quantity}">st
              <button class="qty-btn"><i class="fas fa-minus-circle" data-id="qty-"></i></button>
              <button class="qty-btn"><i class="fas fa-plus-circle" data-id="qty+"></i></button>
            </td>
            <td>
            <span>${order[product].quantity * order[product].price} SEK</span>
            </td>
          </tr>
    `;
      })
      .join("");

    productTable += `</tbody></table>`;

    productSection.innerHTML = productTable;
  } else {
    productSection.innerHTML = "<h4>Your cart is empty.</h4>";
  }
}

/* const changeQtyCheckout = () => {
  document.addEventListener("click", (e) => {
    const productId = e.target.parentNode.parentNode.parentNode.dataset.name;
    console.log(e.target.id);

    if (e.target.id == "checkoutQty+") {
      checkStock(productId);
      console.log("plusknappen");
    } else if (e.target.id == "checkoutQty-") {
      order[productId].quantity == 1 ? null : order[productId].quantity--;
    }
    localStorage.setItem("cart", JSON.stringify(order));
    console.log(localStorage.cart);

    renderOrderSummary();
  });
}; */

/* changeQtyCheckout(); */
