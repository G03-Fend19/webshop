function validateSearchForm() {
  let errormsg = [];
  const searchValue = document.forms["search_form"]["search"].value;


  if (searchValue == "" || searchValue.length < 2) {
    errormsg.push("Use min 2 characters.");
  }
  if (searchValue.length > 50) {
    errormsg.push("Use max 50 characters.");
  }


  if (errormsg.length != 0) {
    showErrormsg(errormsg);

    return false;
  }
}

function showErrormsg(messages) {
  let search = document.forms['search_form']['search'];


  search.value = "";
  search.placeholder = messages
    .map((msg) => {
      return msg;
    })
    .join("");
}
