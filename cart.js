;(() => {
  const addBtn = document.querySelectorAll(".add-to-cart-btn")
  const getCart = () => {
    cart = JSON.parse(localStorage.getItem("cart"))
    if (!cart) {
      cart = {}
    }
  }
  getCart()
  // eventlistener for add-to-cart-btn
  // each button has data-information about their specific product. we send that information
  // to the createProduct function
  addBtn.forEach((btn) =>
    btn.addEventListener("click", (e) => {
      const productData = e.target.parentNode.dataset
      createProduct(productData)
    })
  )
  // we check the cart object if product we want to add already exists, if so pressing  add-product only increases
  // quantity.
  const createProduct = (productData) => {
    if (cart[productData.id]) {
      cart[productData.id].quantity++
    } else {
      cart = {
        ...cart,
        [productData.id]: {
          id: productData.id,
          name: productData.name,
          price: productData.price,
          quantity: 1,
          stock: productData.stock,
        },
      }
    }

    localStorage.setItem("cart", JSON.stringify(cart))
    renderCart()
  }

  const calcTotal = () => {
    let total = 0
    Object.keys(cart).forEach((product) => {
      total += cart[product].price * cart[product].quantity
    })
    localStorage.setItem("total", JSON.stringify(total))
    return `<div>${total}</div>`
  }

  const productsInCart = () => {
    let total = 0
    Object.keys(cart).forEach((el) => {
      total += cart[el].quantity
    })
  }

  const renderCart = () => {
    const fulCart = document.querySelector(".fulCart")
    fulCart.innerHTML = ""

    fulCart.innerHTML += Object.keys(cart).map((el) => {
      return `
      <div data-id=${cart[el].id}>
      <button id="qty-" class="changeQty">-</button>
      ${cart[el].quantity} * ${cart[el].name}
      <button id="qty+" class="changeQty">+</button>
      <button  class="delete-product">delete</button>
      <div>${cart[el].quantity * cart[el].price}</div>
      </div>
      `
    })
    fulCart.innerHTML += calcTotal()
  }
  renderCart()

  // a clicklistener on entire document. fires when user presses
  // anything with class changeQty
  // if the target id = + or -, we add or subtract 1 to corresponding products quantity in cart
  // we also check against current stock-value. We cannot add more products than stock allow.

  const changeQty = () => {
    document.addEventListener("click", (e) => {
      const productId = e.target.parentNode.dataset.id

      if (e.target.className == "changeQty") {
        if (e.target.id == "qty+") {
          cart[productId].quantity <= cart[productId].stock
            ? cart[productId].quantity++
            : alert("no more products in stock")
        } else if (e.target.id == "qty-") {
          cart[productId].quantity == 1 ? null : cart[productId].quantity--
        }
        localStorage.setItem("cart", JSON.stringify(cart))
        renderCart()
      }
    })
  }

  const deleteProduct = () => {
    document.addEventListener("click", (e) => {
      const productId = e.target.parentNode.dataset.id
      if (e.target.className == "delete-product") {
        let r = confirm("are you sure?")
        if (r) {
          delete cart[productId]
          localStorage.setItem("cart", JSON.stringify(cart))
          renderCart()
        }
      }
    })
  }
  changeQty()
  deleteProduct()
})()
