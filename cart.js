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

  // we check the cart object if the product we want to add already exists, if so pressing  add-product only increases
  // quantity.
  // if item is new to cart, we create a new cart variable, spread everything else back in, with the new product
  const createProduct = (productData) => {
    if (cart[productData.id]) {
      checkStock(productData.id)
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

  // check stock takes current Product
  // as long as quantity is lower than stock,  user is allowed to put more of that product in the cart.

  const checkStock = (product) => {
    const q = cart[product].quantity
    const s = cart[product].stock
    q < s ? cart[product].quantity++ : alert("out of stock")
  }

  const calcTotal = () => {
    total = Object.keys(cart).reduce((acc, cur) => {
      return acc + cart[cur].price * cart[cur].quantity
    }, 0)
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

    fulCart.innerHTML += Object.keys(cart).map((product) => {
      return `
      <div data-id=${cart[product].id}>
      <button id="qty-" class="changeQty">-</button>
      ${cart[product].quantity} * ${cart[product].name}
      <button id="qty+" class="changeQty">+</button>
      <button  class="delete-product">delete</button>
      <div>${cart[product].quantity * cart[product].price}</div>
      </div>
      `
    })
    fulCart.innerHTML += calcTotal()
  }
  renderCart()

  // a clicklistener on entire document. fires when user presses
  // anything with class changeQty
  // if the target id = + or -, we add or subtract 1 to corresponding products quantity in cart

  const changeQty = () => {
    document.addEventListener("click", (e) => {
      const productId = e.target.parentNode.dataset.id

      if (e.target.className == "changeQty") {
        if (e.target.id == "qty+") {
          checkStock(productId)
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
