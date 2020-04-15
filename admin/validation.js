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
  if (description == "" || description.length < minDescrip) {
    errormsg.push(
      `The product must have a description of at least ${minDescrip} characters.`
    );
  }
  if (category == "category" || !category) {
    errormsg.push("The product must belong to a category.");
  }
  if (!price) {
    errormsg.push("The product must have a price.");
  }
  if (!qty) {
    errormsg.push("Please set a stock quanitiy for the product.");
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
