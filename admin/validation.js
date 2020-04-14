function validateProductForm() {
  let title = document.forms["addProductForm"]["title"].value;
  console.log(title.length);

  if (title == "" || title.length < 2) {
    alert("Name must be filled out");
    return false;
  }
}
