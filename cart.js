;(() => {
  const addBtn = document.querySelectorAll(".add-to-cart-btn")
  const cartDisplay = document.querySelector(".cart")
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
    if (cart[productData.name]) {
      checkStock(productData.name)
    } else {
      cart = {
        ...cart,
        [productData.name]: {
          id: productData.id,
          img: productData.img,
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
    q < s ? cart[product].quantity++ : alert("no more in stock")
  }

  const calcTotal = () => {
    total = Object.keys(cart).reduce((acc, cur) => {
      return acc + cart[cur].price * cart[cur].quantity
    }, 0)
    localStorage.setItem("total", JSON.stringify(total))
    return `<div>${total}</div>`
  }
  // for counting numbers of products in cart, currently not in use
  const productsInCart = () => {
    let total = 0
    Object.keys(cart).forEach((el) => {
      total += cart[el].quantity
    })
  }

  const renderCart = () => {
    cartDisplay.innerHTML = ""

    cartDisplay.innerHTML += Object.keys(cart).map((product) => {
      return `
      <div class="cart__product" data-name='${cart[product].name}'>
      <div class="cart__product__image-wrapper">
        <img class="cart__product__image-wrapper__img" src="./media/product_images/${
          cart[product].img
        }"></img>
      </div>
      <div class="cart__product__info"> 
      <p>
           ${cart[product].name}
      </p>
        
      <div class="cart__product__info__btns">
      <p>${cart[product].quantity}</p>
      <button id="qty-" class="changeQty">-</button>
      <button id="qty+" class="changeQty">+</button>
      <button  class="delete-product">delete</button>
      
      </div>
      <p>${cart[product].quantity * cart[product].price}</p>
      
      </div>
      </div>
      `
    })

    cartDisplay.innerHTML += calcTotal()
    cartDisplay.innerHTML += `<button class="clear-cart">Clear cart</button>`
  }
  renderCart()

  // a clicklistener on entire document. fires when user presses
  // anything with class changeQty
  // if the target id = + or -, we add or subtract 1 to corresponding products quantity in cart

  const changeQty = () => {
    document.addEventListener("click", (e) => {
      const productId = e.target.parentNode.parentNode.parentNode.dataset.name
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
      const productId = e.target.parentNode.parentNode.parentNode.dataset.name
      if (e.target.className == "delete-product") {
        console.log(productId)
        let r = confirm("are you sure?")
        if (r) {
          delete cart[productId]
          localStorage.setItem("cart", JSON.stringify(cart))
          renderCart()
        }
      }
    })
  }
  const clearCart = () => {
    document.addEventListener("click", (e) => {
      if (
        e.target.className == "clear-cart" &&
        !Object.entries(cart).length == 0
      ) {
        let r = confirm("u want to clear the cart?")
        if (r) {
          cart = {}
          localStorage.setItem("cart", JSON.stringify(cart))
          renderCart()
        }
      }
    })
  }
  const cartBtn = document.querySelector(".fa-shopping-cart")

  cartBtn.addEventListener("click", () => {
    cartDisplay.classList.toggle("hidden")
  })
  changeQty()
  deleteProduct()
  clearCart()
})()
