function validateProductForm() {
  let errormsg = [];
  const title = document.forms["addProductForm"]["title"].value;
  const description = document.forms["addProductForm"]["description"].value;
  const category = document.forms["addProductForm"]["category"].value;
  const price = document.forms["addProductForm"]["price"].value;
  const qty = document.forms["addProductForm"]["qty"].value;

  const minDescrip = 10;

  if (title == "" || title.length < 2) {
    errormsg.push("The product must have a name of at least 2 characters.");
  }
  if (title.length > 50) {
    errormsg.push("The product name can't be more than 50 characters.");
  }
  if (description == "" || description.length < minDescrip) {
    errormsg.push(
      `The product must have a description of at least ${minDescrip} characters.`
    );
    if (description.length > 800) {
      errormsg.push(
        `The product description can't be more than 800 characters.`
      );
  }
  if (category == "category" || !category) {
    errormsg.push("The product must belong to a category.");
  }
  if (!price) {
    errormsg.push("The product must have a price.");
  }
  if (price < 0) {
    errormsg.push("The product price can't be less than 0.");
  }
  if (!qty) {
    errormsg.push("Please set a stock quanitiy for the product.");
  }
  if (qty < 0) {
    errormsg.push("The product quantity can't be less than 0.");
  }

  if (errormsg.length != 0) {
    showErrormsg(errormsg);

    return false;
  }
}

function showErrormsg(messages) {
  console.log(messages);

  let errorDiv = document.getElementById("errorDiv");
  errorDiv.innerHTML = "";
  errorDiv.innerHTML = messages
    .map((msg) => {
      return `<p class="errormsg">${msg}</p>`;
    })
    .join("");
}
