(() => {
  window.addEventListener("pageshow", function (event) {
    var historyTraversal =
      event.persisted ||
      (typeof window.performance != "undefined" &&
        window.performance.navigation.type === 2);
    if (historyTraversal) {
      window.location.reload();
    }
  });
  let cartCount = document.querySelector(".cart_qty_show");
  const addBtn = document.querySelectorAll(".add-to-cart-btn");
  const htmlEl = document.querySelector("html");
  const body = document.querySelector("body");
  const cartDisplay = document.querySelector(".cart");
  const productWrapper = document.querySelector(".cart__product-wrapper");
  const totalCheckout = document.querySelector(".cart__total-checkout");
  const cartMenu = document.querySelector(".cart__menu");
  const getCart = () => {
    cart = JSON.parse(localStorage.getItem("cart"));
    !cart ? (cart = {}) : null;
  };
  getCart();

  const addDiscountToProducts = () => {
    const lastDate = new Date();
    lastDate.setFullYear(lastDate.getFullYear() - 1);

    allProductsFromPHP.forEach((product) => {
      const productDate = new Date(product.AddedDate);

      discount = 1;
      if (parseInt(product.ProductQty) < 10) {
        discount = 0.9;
      }
      product.discount = discount;
    });
  };
  addDiscountToProducts();
  // eventlistener for add-to-cart-btn
  // each button has a ID matching one product from the database

  addBtn.forEach((btn) =>
    btn.addEventListener("click", (e) => {
      cartCount.classList.remove("hidden");

      const [productData] = allProductsFromPHP.filter((product) => {
        return product.ProductId === e.target.dataset.id;
      });

      let qty;
      document.querySelector("#qtyInput")
        ? (qty = document.querySelector("#qtyInput").value)
        : (qty = 1);
      qty;
      createProduct(productData, qty);
    })
  );
  addBtn.forEach((btn) =>
    btn.addEventListener("click", (e) => {
      // Btn animation
      btn.classList.add("elementToFadeInAndOut");
      setTimeout(function () {
        btn.classList.remove("elementToFadeInAndOut");
      }, 4000);

      cartCount.classList.remove("hidden");
      const productData = e.target.dataset;
      let qty;
      document.querySelector("#qtyInput-product-page")
        ? (qty = document.querySelector("#qtyInput-product-page").value)
        : (qty = 1);
      qty === NaN ? (qty = 1) : (qty = qty);

      createProduct(productData, qty);
    })
  );

  const createProduct = (productData, qty) => {
    if (productData.ProductId !== undefined) {
      if (cart[productData.ProductId]) {
      } else {
        cart = {
          ...cart,
          [productData.ProductId]: {
            id: productData.ProductId,
            img: productData.ImageName,
            name: productData.ProductName,
            price: productData.ProductPrice,
            discount: parseFloat(productData.discount),
            quantity: qty,
            stock: productData.ProductQty,
          },
        };
      }
      localStorage.setItem("cart", JSON.stringify(cart));

      renderCart();
      hideAndShowCartBtns();
    }
  };
  const getStock = (id) => {
    let [product] = allProductsFromPHP.filter((product) => {
      return product.ProductId === id;
    });

    stock = product.ProductQty;
    return stock;
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
  const hideAndShowCartBtns = () => {
    const qtyInformation = document.querySelectorAll(".amount");
    const cartKeys = Object.keys(cart);

    // hide addToCartBtn
    const hideAddToCartBtn = () => {
      addBtn.forEach((btn) => {
        btn.addEventListener("click", () => {
          btn.classList.add("hidden");
        });
        cartKeys.includes(btn.dataset.id)
          ? btn.classList.add("hidden")
          : btn.classList.remove("hidden");
      });
      showQtyInformation = () => {
        qtyInformation.forEach((field) => {
          cartKeys.includes(field.dataset.id)
            ? field.classList.remove("hidden")
            : field.classList.add("hidden");
        });
      };
      showQtyInformation();
    };

    hideAddToCartBtn();
  };
  hideAndShowCartBtns();

  const setQuantityInputs = () => {
    const quantityInputs = document.querySelectorAll(".qty-input");

    quantityInputs.forEach((input) => {
      if (cart[input.dataset.productid]) {
        input.value = cart[input.dataset.productid].quantity;
      }
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
       <i id="delete-product" class="fas fa-trash"></i></button>
        <button class="close-cart">
        Close Cart <i class="far fa-times-circle"></i>
        </button>`;
      productWrapper.innerHTML += Object.keys(cart)
        .map((product) => {
          let image;
          !cart[product].img
            ? (image = "placeholder.jpg")
            : (image = cart[product].img);
          priceDisplay = "";
          if (cart[product].discount === 1) {
            priceDisplay = `<p class='price'> ${Math.ceil(
              cart[product].quantity * cart[product].price
            )} SEK</p>`;
          } else {
            priceDisplay = `<p class='price__line-through'> ${
              cart[product].quantity * cart[product].price
            } SEK</p>
                            <p class='price__discount'> ${Math.ceil(
                              cart[product].quantity *
                                (cart[product].price * cart[product].discount)
                            )} SEK</p>`;
          }
          return `
      <div class="cart__product" data-name='${cart[product].name}' data-id='${cart[product].id}'>
      <div class="cart__product__image-wrapper">
        <img class="cart__product__image-wrapper__img" src="./media/product_images/${image}"></img>
      </div>
      <div class="cart__product__info"> 
      <p>
           ${cart[product].name}
      </p>
      <div class="cart__product__info__btns">
      <input type="number" min="1"  data-productId='${cart[product].id}' class="cart__product__info__btns__qty qty-input" value="${cart[product].quantity}">

      <i data-id="qty-" data-productId='${cart[product].id}' data-value="-1" class="changeQty fas fa-minus-circle "></i>
      <i data-id="qty+" data-productId='${cart[product].id}' data-value="1" class="changeQty fas fa-plus-circle open-modal"></i>
      <i data-id="delete-product"class="delete-product fas fa-trash"></i>
    
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
        `<a href="checkout_page.php#main-checkout"><button class="cart__checkout">Go To Checkout</button></a>`;
      productsInCart();
    }
    hideAndShowCartBtns();
    setQuantityInputs();

    if (window.location.href.indexOf("checkout") !== -1) {
      calcTotalWithShipping();
      renderOrderSummary();
    }
  };
  renderCart();

  const changeQty = () => {
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("changeQty")) {
        const productId = e.target.dataset.productid;

        const stock = getStock(productId);

        if (cart[productId].quantity < stock) {
          cart[productId].quantity += parseFloat(e.target.dataset.value);
        } else if (e.target.dataset.value == -1) {
          cart[productId].quantity += parseFloat(e.target.dataset.value);
        } else {
          // do the modal
          const modal = document.getElementById("noMoreInStockModal");
          const span = document.getElementsByClassName("close")[0];
          modal.style.display = "block";

          span.onclick = function () {
            modal.style.display = "none";
          };
          window.onclick = function (event) {
            if (event.target == modal) {
              modal.style.display = "none";
            }
          };
          document.addEventListener("click", (e) => {
            if (e.target.className.includes("cancel-btn")) {
              modal.style.display = "none";
            }
          });
        }

        if (cart[productId].quantity <= 0) {
          cart[productId].quantity = 1;
        }

        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
      }
    });

    // adding Qty To input-value field
    document.addEventListener("input", (e) => {
      if (e.target.classList.contains("qty-input")) {
        const productId = e.target.dataset.productid;
        const stock = getStock(productId);
        const inputNumber = parseInt(e.target.value);

        if (inputNumber < stock) {
          cart[productId].quantity = inputNumber;
        }
        if (inputNumber <= 0) {
          cart[productId].quantity = 1;
        }

        localStorage.setItem("cart", JSON.stringify(cart));
      }
      renderCart();
    });
  };

  const deleteProduct = () => {
    document.addEventListener("click", (e) => {
      // const productId = e.target.parentNode.parentNode.parentNode.dataset.name;

      if (e.target.dataset.id == "delete-product") {
        const productId = e.target.parentNode.parentNode.parentNode.dataset.id;
        delete cart[productId];

        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
        if (document.querySelector("#pTable-section")) {
          renderOrderSummary();
          calcTotalWithShipping();
          if (Object.entries(cart).length == 0) {
            const confirmForm = document.getElementById("confirm-order");
            const productSection = document.querySelector("#pTable-section");
            confirmForm.classList.add("hidden");
            productSection.innerHTML = "<h3>Your cart is empty.</h3>";
          }
        }
      }
    });
  };
  const clearCart = () => {
    const modal = document.getElementById("myModal");
    const span = document.getElementsByClassName("close")[0];
    const deleteIcon = document.getElementById("delete-product");

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
        if (document.querySelector("#pTable-section")) {
          renderOrderSummary();
          calcTotalWithShipping();
          const confirmForm = document.getElementById("confirm-order");
          const productSection = document.querySelector("#pTable-section");
          confirmForm.classList.add("hidden");
          productSection.innerHTML = "<h3>Your cart is empty.</h3>";
        }
      }
    });
  };
  const cartBtn = document.querySelector(".fa-shopping-cart");
  cartBtn.addEventListener("click", () => {
    cartDisplay.classList.toggle("hidden");
    body.classList.toggle("noScroll");
    htmlEl.classList.toggle("noScroll");
    cartDisplay.toggleAttribute("aria-hidden", false);
  });
  const closeCart = () => {
    document.addEventListener("click", (e) => {
      if (e.target.className == "close-cart") {
        cartDisplay.classList.toggle("hidden");
        body.classList.toggle("noScroll");
        htmlEl.classList.toggle("noScroll");
        cartDisplay.toggleAttribute("aria-hidden", true);
      }
    });
  };
  const removeSoldOutProducts = () => {
    if (typeof soldOutProductsFromPHP !== "undefined") {
      Object.keys(soldOutProductsFromPHP).forEach((key) => {
        delete cart[key];
      });
      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();
      renderOrderSummary();
      calcTotalWithShipping();
    }
  };
  const changeProductQtyToMatchDB = () => {
    if (typeof productsToReduceFromPHP !== "undefined") {
      Object.keys(productsToReduceFromPHP).forEach((key) => {
        let qtyInStock = parseInt(productsToReduceFromPHP[key].ProductsLeft);
        cart[key].quantity = qtyInStock;
      });
      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();
      renderOrderSummary();
      calcTotalWithShipping();
    }
  };

  // We listen to the resize event
  window.addEventListener("resize", () => {
    // We execute the same script as before
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty("--vh", `${vh}px`);
  });

  changeQty();
  deleteProduct();
  clearCart();
  closeCart();
  removeSoldOutProducts();
  changeProductQtyToMatchDB();
})();
