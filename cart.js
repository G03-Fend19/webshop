(() => {
  const cartCount = document.querySelector(".cart_qty_show");
  const addBtn = document.querySelectorAll(".add-to-cart-btn");
  const cartDisplay = document.querySelector(".cart");
  const productWrapper = document.querySelector(".cart__product-wrapper");
  const totalCheckout = document.querySelector(".cart__total-checkout");
  const cartMenu = document.querySelector(".cart__menu");
  const getCart = () => {
    cart = JSON.parse(localStorage.getItem("cart"));
    !cart ? (cart = {}) : null;
  };
  getCart();
  // eventlistener for add-to-cart-btn
  // each button has data-information about their specific product. we send that information
  // to the createProduct function
  addBtn.forEach((btn) =>
    btn.addEventListener("click", (e) => {
      cartCount.classList.remove("hidden");
      const productData = e.target.parentNode.dataset;
      let qty;
      document.querySelector("#qtyInput")
        ? (qty = document.querySelector("#qtyInput").value)
        : (qty = 1);
      qty;
      createProduct(productData, qty);
    })
  );

  // we check the cart object if the product we want to add already exists, if so pressing  add-product only increases
  // quantity.
  // if item is new to cart, we create a new cart variable, spread everything else back in, with the new product
  const createProduct = (productData, qty) => {
    if (cart[productData.name]) {
      updateStock(productData.name, qty);
    } else {
      cart = {
        ...cart,
        [productData.name]: {
          id: productData.id,
          img: productData.img,
          name: productData.name,
          price: productData.price,
          discount: parseFloat(productData.discount),
          quantity: qty,
          stock: productData.stock,
        },
      };
    }
    localStorage.setItem("cart", JSON.stringify(cart));

    renderCart();
  };

  // check stock takes current Product
  // as long as quantity is lower than stock,  user is allowed to put more of that product in the cart.
  const updateStock = (product, qty) => {
    const q = cart[product].quantity;
    const s = cart[product].stock;
    q <= s ? (cart[product].quantity = qty) : alert("no more in stock");
  };
  const checkStock = (product) => {
    const q = cart[product].quantity;
    const s = cart[product].stock;
    q < s ? cart[product].quantity++ : alert("no more in stock");
  };
  const calcTotal = () => {
    total = Object.keys(cart).reduce((acc, cur) => {
      return (
        acc +
        Math.ceil(cart[cur].price * cart[cur].discount * cart[cur].quantity)
      );
    }, 0);
    localStorage.setItem("total", JSON.stringify(total));
    return `<div class="cart__total"><p class="priceHeadline">Total price</p> <p class="totalSum">${total} SEK</p></div>`;
  };
  // for counting numbers of products in cart, currently not in use
  const productsInCart = () => {
    let total = 0;
    Object.keys(cart).forEach((el) => {
      total += parseInt(cart[el].quantity);
      cartCount.textContent = total;
    });
  };

  const renderCart = () => {
    if (Object.entries(cart).length === 0) {
      productWrapper.innerHTML = "No products in cart";
      totalCheckout.innerHTML = "";
      cartCount.textContent = "";
      cartCount.classList.add("hidden");
    } else {
      productsInCart();
      productWrapper.innerHTML = "";
      cartMenu.innerHTML = "";
      totalCheckout.innerHTML = "";
      cartMenu.innerHTML += `
 
 
       <button class="open-modal" id="myBtn">
       Clear Cart 
       <i id="delete-product"class="fas fa-trash-alt"></i></button>
        <button class="close-cart">
        Close Cart <i class="far fa-times-circle"></i>
        </button>`;
      productWrapper.innerHTML += Object.keys(cart)
        .map((product) => {
          priceDisplay = "";
          if (cart[product].discount === 1) {
            console.log(cart[product].discount);
            priceDisplay = `<p class='price'> ${
              cart[product].quantity * cart[product].price
              } SEK</p>`;
          } else {
            console.log("discount");
            priceDisplay = `<p class='price__line-through'> ${
              cart[product].quantity * cart[product].price
              } SEK</p>
                            <p class='price__discount'> ${Math.ceil(
                cart[product].quantity *
                (cart[product].price * cart[product].discount)
              )} SEK</p>`;
          }
          return `
      <div class="cart__product" data-name='${cart[product].name}'>
      <div class="cart__product__image-wrapper">
        <img class="cart__product__image-wrapper__img" src="./media/product_images/${cart[product].img}"></img>
      </div>
      <div class="cart__product__info"> 
      <p>
           ${cart[product].name}
      </p>
      <div class="cart__product__info__btns">
      <input type=number id="quantity-input" min="1" max="${cart[product].stock}" class="cart__product__info__btns__qty" 
      value="${cart[product].quantity}">
      </input>
      <i data-id="qty-" class="changeQty fas fa-minus-circle "></i>
      <i data-id="qty+" class="changeQty fas fa-plus-circle "></i>
      <i data-id="delete-product"class="delete-product fas fa-trash-alt"></i>

      </div>
      <div class='cart__product__info__price'>
      ${priceDisplay}
      </div>
      </div>
      </div>
      `;
        })
        .join("");
      totalCheckout.innerHTML +=
        calcTotal() +
        `<button class="cart__checkout"><a href="checkout_page.php#main-checkout" >Go To Checkout</a></button>`;
      productsInCart();
    }

    // cartDisplay.innerHTML += `<button class="cart__checkout">Go to Checkout</button></div>`;
  };
  renderCart();

  // a clicklistener on entire document. fires when user presses
  // anything with class changeQty
  // if the target id = + or -, we add or subtract 1 to corresponding products quantity in cart
  const changeQty = () => {
    document.addEventListener("click", (e) => {
      if (e.target.dataset.id == "qty+") {
        let productId = e.target.parentNode.parentNode.parentNode.dataset.name;
        checkStock(productId);
      } else if (e.target.dataset.id == "qty-") {
        let productId = e.target.parentNode.parentNode.parentNode.dataset.name;
        cart[productId].quantity == 1 ? null : cart[productId].quantity--;
      }

      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();

      if (document.querySelector("#pTable-section")) {
        renderOrderSummary();
        calcTotalWithShipping();
      }
    });
  };
  const deleteProduct = () => {
    document.addEventListener("click", (e) => {
      if (e.target.dataset.id == "delete-product") {
        const productId =
          e.target.parentNode.parentNode.parentNode.dataset.name;
        delete cart[productId];
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
        if (document.querySelector("#pTable-section")) {
          renderOrderSummary();
          calcTotalWithShipping();

        }
      }
    });
  };

  const clearCart = () => {
    const modal = document.getElementById("myModal");
    const span = document.getElementsByClassName("close")[0];

    document.addEventListener("click", (e) => {
      if (e.target.className == "open-modal") {
        modal.style.display = "block";
        //close the modal
        span.onclick = function () {
          modal.style.display = "none";
        };
        // clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        };
        document.addEventListener("click", (e) => {
          if (e.target.className == "cancel-btn") {
            modal.style.display = "none";
          }
        });
      }
    });

    document.addEventListener("click", (e) => {
      if (
        e.target.className == "clear-cart" &&
        !Object.entries(cart).length == 0
      ) {
        cart = {};
        modal.style.display = "none";
        localStorage.setItem("cart", JSON.stringify(cart));
        cartMenu.innerHTML = "";
        renderCart();
      }
    });
  };
  const cartBtn = document.querySelector(".fa-shopping-cart");
  cartBtn.addEventListener("click", () => {
    cartDisplay.classList.toggle("hidden");
  });
  const closeCart = () => {
    document.addEventListener("click", (e) => {
      if (e.target.className == "close-cart") {
        cartDisplay.classList.toggle("hidden");
      }
    });
  };
  changeQty();
  deleteProduct();
  clearCart();
  closeCart();
})();

// ;(() => {
//   const addBtn = document.querySelectorAll(".add-to-cart-btn")
//   const cartDisplay = document.querySelector(".cart")
//   const getCart = () => {
//     cart = JSON.parse(localStorage.getItem("cart"))
//     !cart ? (cart = {}) : null
//   }
//   getCart()
//   // eventlistener for add-to-cart-btn
//   // each button has data-information about their specific product. we send that information
//   // to the createProduct function
//   addBtn.forEach((btn) =>
//     btn.addEventListener("click", (e) => {
//       const productData = e.target.parentNode.dataset
//       createProduct(productData)
//     })
//   )

//   // we check the cart object if the product we want to add already exists, if so pressing  add-product only increases
//   // quantity.
//   // if item is new to cart, we create a new cart variable, spread everything else back in, with the new product
//   const createProduct = (productData) => {
//     if (cart[productData.name]) {
//       checkStock(productData.name)
//     } else {
//       cart = {
//         ...cart,
//         [productData.name]: {
//           id: productData.id,
//           img: productData.img,
//           name: productData.name,
//           price: productData.price,
//           quantity: 1,
//           stock: productData.stock,
//         },
//       }
//     }

//     localStorage.setItem("cart", JSON.stringify(cart))
//     renderCart()
//   }

//   // check stock takes current Product
//   // as long as quantity is lower than stock,  user is allowed to put more of that product in the cart.
//   const updateStock = () => {}
//   const checkStock = (product) => {
//     const q = cart[product].quantity
//     const s = cart[product].stock
//     q < s ? cart[product].quantity++ : alert("no more in stock")
//   }

//   const calcTotal = () => {
//     total = Object.keys(cart).reduce((acc, cur) => {
//       return acc + cart[cur].price * cart[cur].quantity
//     }, 0)
//     localStorage.setItem("total", JSON.stringify(total))
//     return `<div class="cart__total"><p>Total price</p> <p>${total} SEK</p></div>`
//   }
//   // for counting numbers of products in cart, currently not in use
//   const productsInCart = () => {
//     let total = 0
//     Object.keys(cart).forEach((el) => {
//       total += cart[el].quantity
//     })
//   }

//   const renderCart = () => {
//     cartDisplay.innerHTML = ""
//     cartDisplay.innerHTML += `
//     <div class="cart__menu"><button class="clear-cart">
//         Clear Cart
//         <i id="delete-product"class="fas fa-trash-alt"></i>
//        </button>
//         <button class="close-cart">
//         Close Cart <i class="far fa-times-circle"></i>
//         </button>
//     </div>`
//     cartDisplay.innerHTML += Object.keys(cart)
//       .map((product) => {
//         return `
//       <div class="cart__product" data-name='${cart[product].name}'>
//       <div class="cart__product__image-wrapper">
//         <img class="cart__product__image-wrapper__img" src="./media/product_images/${
//           cart[product].img
//         }"></img>
//       </div>
//       <div class="cart__product__info">
//       <p>
//            ${cart[product].name}
//       </p>

//       <div class="cart__product__info__btns">
//       <input type=text  class="cart__product__info__btns__qty"
//       value="${cart[product].quantity}">

//       </input>

//       </div>
//       <p> ${cart[product].quantity * cart[product].price} SEK</p>

//       </div>
//       </div>
//       `
//       })
//       .join("")

//     cartDisplay.innerHTML += calcTotal()
//     cartDisplay.innerHTML += `<div class="cart__checkout"><button>Go to Checkout</button></div>`
//   }
//   renderCart()

//   // a clicklistener on entire document. fires when user presses
//   // anything with class changeQty
//   // if the target id = + or -, we add or subtract 1 to corresponding products quantity in cart

//   const changeQty = () => {
//     document.addEventListener("click", (e) => {
//       const productId = e.target.parentNode.parentNode.parentNode.dataset.name
//       console.log(e.target.id)

//       localStorage.setItem("cart", JSON.stringify(cart))
//       renderCart()

//       if (document.querySelector("#pTable-section")) {
//         renderOrderSummary()
//       }
//     })
//   }

//   const deleteProduct = () => {
//     document.addEventListener("click", (e) => {
//       const productId = e.target.parentNode.parentNode.parentNode.dataset.name

//         console.log(productId)
//         let r = confirm("are you sure?")
//         if (r) {
//           delete cart[productId]
//           localStorage.setItem("cart", JSON.stringify(cart))
//           renderCart()
//           if (document.querySelector("#pTable-section")) {
//             renderOrderSummary()
//           }
//         }
//       }
//     })
//   }
//   const clearCart = () => {
//     document.addEventListener("click", (e) => {
//       if (
//         e.target.className == "clear-cart" &&
//         !Object.entries(cart).length == 0
//       ) {
//         let r = confirm("u want to clear the cart?")
//         if (r) {
//           cart = {}
//           localStorage.setItem("cart", JSON.stringify(cart))
//           renderCart()
//         }
//       }
//     })
//   }
//   const cartBtn = document.querySelector(".fa-shopping-cart")

//   cartBtn.addEventListener("click", () => {
//     cartDisplay.classList.toggle("hidden")
//   })

//   const closeCart = () => {
//     document.addEventListener("click", (e) => {
//       if (e.target.className == "close-cart") {
//         cartDisplay.classList.toggle("hidden")
//       }
//     })
//   }

//   changeQty()
//   deleteProduct()
//   clearCart()
//   closeCart()
// })()
