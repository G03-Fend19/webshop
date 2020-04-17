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
  }
  if (description.length > 800) {
    errormsg.push(`The product description can't be more than 800 characters.`);
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

function drag_start(event) {
  var style = window.getComputedStyle(event.target, null);
  event.dataTransfer.setData(
    "text/plain",
    parseInt(style.getPropertyValue("left"), 10) -
      event.clientX +
      "," +
      (parseInt(style.getPropertyValue("top"), 10) - event.clientY)
  );
}
function drag_over(event) {
  event.preventDefault();
  return false;
}
function drop(event) {
  var offset = event.dataTransfer.getData("text/plain").split(",");
  var dm = document.getElementById("dragme");
  dm.style.left = event.clientX + parseInt(offset[0], 10) + "px";
  dm.style.top = event.clientY + parseInt(offset[1], 10) + "px";
  event.preventDefault();
  return false;
}
var dm = document.getElementById("dragme");
dm.addEventListener("dragstart", drag_start, false);
document.body.addEventListener("dragover", drag_over, false);
document.body.addEventListener("drop", drop, false);

(() => {
  const addImgBtn = document.querySelector(".add-img");
  const uploadForm = document.querySelector(".upload-form");
  const cancelImgUpload = document.querySelector(".cancel-upload");

  cancelImgUpload.addEventListener("click", () => {
    uploadForm.classList.toggle("hidden");
  });
  addImgBtn.addEventListener("click", () => {
    console.log("test");

    uploadForm.classList.toggle("hidden");
  });
})();
