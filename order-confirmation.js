(() => {
  // gathering all the fields from the DOM and converting them to an Array.
  const customerInformation = document.querySelectorAll(".customer-info");
  const customerInformationArray = Array.prototype.slice.call(
    customerInformation
  );

  // looping over that array and creates the customer object.
  // when the loop encounters a checkbox, instead of choosing value, we get the checked boolean.
  createCustomerObject = () => {
    let customerInformation;
    const customer = customerInformationArray.reduce((acc, cur) => {
      cur.type !== "checkbox"
        ? (customerInformation = cur.value)
        : (customerInformation = cur.checked);
      return (acc = {
        ...acc,
        [cur.id]: customerInformation,
      });
    }, {});
    return customer;
  };
  orderFormSubmission = (event) => {
    event.preventDefault();
    const customerObject = createCustomerObject();
    localStorage.setItem("customer", JSON.stringify(customerObject));
  };
})();
