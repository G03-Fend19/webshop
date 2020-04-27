(() => {
  const confirmForm = document.getElementById("confirm-order");

  confirmForm.addEventListener("submit", (e) => {
    // gathering all the fields from the DOM and converting them to an Array.
    const customerInformation = document.querySelectorAll(".customer-info");
    const customerInformationArray = Array.prototype.slice.call(
      customerInformation
    );

    // looping over that array and creates the customer object.
    // when the loop encounters a checkbox, instead of choosing value, we get the checked boolean.
    createCustomerObject = () => {
      const customer = customerInformationArray.reduce((acc, cur) => {
        return (acc = {
          ...acc,
          [cur.id]: cur.type === "checkbox" ? cur.checked : cur.value,
        });
      }, {});
      return customer;
    };

    const customerObject = createCustomerObject();
    localStorage.setItem("customer", JSON.stringify(customerObject));
  });
})();
