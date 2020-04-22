let order = JSON.parse(localStorage.cart);
const productSection = document.querySelector("#pTable-section");

renderOrderSummary();

function renderOrderSummary() {
  if (order) {
    console.log(order);

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
          <tr>
            <td><i class="fas fa-trash"></i></td>
            <td>
              <img class="order-summary__img" src="./media/product_images/${
                order[product].img
              }" alt="Product image">
            </td>
            <td>${order[product].name}</td>
            <td><span>${order[product].quantity} st</span></td>
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
    /* <h4>No products in this category</h4> */
  }
  console.log(order);
}
