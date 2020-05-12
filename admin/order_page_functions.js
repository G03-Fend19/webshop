let position = parseFloat(localStorage.getItem("page_position"));
if (position) {
  // console.log(position);
  document.documentElement.scrollTop = document.body.scrollTop = position + 200;
  window.scrollTo(0, position);
  localStorage.removeItem("page_position");
}
// let rowId = localStorage.getItem('row');
// if (rowId) {
//   let row = document.getElementById(`${rowId}`)
//   let rowPosition = row.getBoundingClientRect().top
//   window.scrollTo(0, rowPosition - 90)
//   console.log(row)
//   localStorage.removeItem('row');
// }
function filterOrders(orders) {
  let orderType;
  Object.keys(orders).forEach(function (order) {
    orderType = orders[order].OrderType;
  });
  //Active orders filter select/input element
  const activeStatusFilter = document.querySelector("#activeStatusFilter");
  const activeTextFilter = document.querySelector("#activeTextFilter");
  //Completed orders filter input element
  const completedTextFilter = document.querySelector("#completedTextFilter");
  //Active/Completed orders tables
  const activeOrdersTable = document.querySelector("#activeOrdersTable");
  const completedOrdersTable = document.querySelector("#completedOrdersTable");
  let ordersToDraw = orders;
  let filterText;
  let filterStatus;
  //Checks if list of orders is active orders
  if (orderType == "active") {
    activeOrdersTable.innerHTML = "";
    //Sets filter values of select/input
    filterText = activeTextFilter.value;
    filterStatus =
      activeStatusFilter.options[activeStatusFilter.selectedIndex].value;
  } else {
    completedOrdersTable.innerHTML = "";
    //Sets filter value of input
    filterText = completedTextFilter.value;
  }
  //If input in any text filter
  if (filterText) {
    //filter ordersToDraw if input matches orders.city
    ordersToDraw = Object.values(ordersToDraw).filter(function (order) {
      return order["DeliveryCity"]
        .toLowerCase()
        .includes(filterText.toLowerCase());
    });
  }
  //If filterStatus is not 'all'
  if (filterStatus && filterStatus !== "all") {
    //filter ordersToDraw if chosen status matches order['OrderStatusId']
    ordersToDraw = Object.values(ordersToDraw).filter(function (order) {
      return order["OrderStatusId"].includes(filterStatus);
    });
  }
  Object.values(ordersToDraw).forEach(function (order, i) {
    const tr = document.createElement("tr");
    const tdId = document.createElement("td");
    tdId.innerHTML = "#" + order["OrderNumber"];
    const tdCustomer = document.createElement("td");
    let fullName = order["CustomerFirstName"] + " " + order["CustomerLastName"];
    const fullNameShort = fullName.substring(0, 20) + "...";
    fullName.length > 20 ? (fullName = fullNameShort) : (fullName = fullName);
    tdCustomer.innerHTML = fullName;
    const tdCity = document.createElement("td");
    tdCity.innerHTML = order["DeliveryCity"];
    const tdDate = document.createElement("td");
    tdDate.innerHTML = order["OrderDate"];
    const tdSum = document.createElement("td");
    tdSum.innerHTML = order["OrderCost"] + " SEK";
    const tdStatus = document.createElement("td");
    if (order.OrderType == "active") {
      const statusForm = document.createElement("form");
      statusForm.setAttribute("id", "shouldUpdate" + order["OrderNumber"]);
      statusForm.setAttribute("action", "./assets/update_order_status.php");
      statusForm.setAttribute("method", "POST");
      const statusSelect = document.createElement("select");
      statusSelect.setAttribute("name", "statusSelect" + order["OrderNumber"]);
      statusSelect.setAttribute("id", "statusSelect" + order["OrderNumber"]);
      statusSelect.setAttribute(
        "onchange",
        "updateStatus(" + order["OrderNumber"] + ")"
      );
      statusSelect.classList.add("select-status");
      const statusOPending = document.createElement("option");
      statusOPending.innerHTML = "Pending";
      statusOPending.setAttribute("value", "1");
      if (order["OrderStatusId"] == statusOPending.value) {
        statusOPending.setAttribute("selected", "selected");
      }
      const statusOInProgress = document.createElement("option");
      statusOInProgress.innerHTML = "In progress";
      statusOInProgress.setAttribute("value", "2");
      if (order["OrderStatusId"] == statusOInProgress.value) {
        statusOInProgress.setAttribute("selected", "selected");
      }
      const statusOCompleted = document.createElement("option");
      statusOCompleted.innerHTML = "Completed";
      statusOCompleted.setAttribute("value", "3");
      if (order["OrderStatusId"] == statusOCompleted.value) {
        statusOCompleted.setAttribute("selected", "selected");
      }
      const hiddenStatusSelectInput = document.createElement("input");
      hiddenStatusSelectInput.setAttribute("type", "hidden");
      hiddenStatusSelectInput.setAttribute("name", "o_id");
      hiddenStatusSelectInput.setAttribute("value", order["OrderNumber"]);
      const hiddenReturnURLInput = document.createElement("input");
      hiddenReturnURLInput.setAttribute("type", "hidden");
      hiddenReturnURLInput.setAttribute("name", "returnUrl");
      let returnUrl = window.location.href;
      // console.log(returnUrl);
      hiddenReturnURLInput.setAttribute("value", returnUrl);
      statusSelect.appendChild(statusOPending);
      statusSelect.appendChild(statusOInProgress);
      statusSelect.appendChild(statusOCompleted);
      statusForm.appendChild(statusSelect);
      statusForm.appendChild(hiddenStatusSelectInput);
      statusForm.appendChild(hiddenReturnURLInput);
      tdStatus.appendChild(statusForm);
    } else {
      tdStatus.innerHTML = order["OrderStatus"];
    }
    const tdOpen = document.createElement("td");
    // const formLink = document.createElement("form");
    // formLink.setAttribute("action", "");
    // formLink.setAttribute("method", "POST");
    const openOrderBtn = document.createElement("button");
    // openOrderBtn.setAttribute("id", "openModal" );
    openOrderBtn.classList.add("open-modal");
    openOrderBtn.innerHTML = "<i class='far fa-eye'></i>";
    const moodal = document.createElement("div");
    moodal.setAttribute("id", "completedOrdersModal");
    moodal.setAttribute("class", "order_overview");

    let sale = "";

    if (
      Date.parse(order["Products"][0].ProductDate) < Date.parse("-1 year") &&
      order["Products"][0].ProductStock < 10
    ) {
      sale = "yes";
    } else {
      sale = "no";
    }

    let productTable = "";

    for (let i = 0; i < order["Products"].length; i++) {
      productTable +=
        `<tr><td> ` +
        order["Products"][i].ProductName +
        `</td><td> ` +
        order["Products"][i].ProductDesc +
        `</td><td> ` +
        order["Products"][i].ProductPrice +
        `</td><td> ` +
        order["Products"][i].ProductQty +
        `</td><td> ` +
        sale +
        `</td></tr>`;
    }

    moodal.innerHTML =
      `
      <div class='order_overview__content'>
        <div class='order_overview__content__header'>
          <span class='close'>&times;</span>
          <h2>Order overview</h2> 
        </div>
        <div class='order_overview__content__body'>
        <p>#` +
      order["OrderNumber"] +
      `</p>
        <p>` +
      order["CustomerFirstName"] +
      " " +
      order["CustomerLastName"] +
      `</p>
        <p>` +
      order["DeliveryStreet"] +
      `</p> 
        <p>` +
      order["DeliveryPostal"] +
      `</p>
        <p>` +
      order["DeliveryCity"] +
      `</p>
        <p>` +
      order["OrderDate"] +
      `</p>
        <table>
          <thead>
            <tr>
              <td>Product</td>
              <td>Description</td>
              <td>Price</td>
              <td>Quantity</td> 
              <td>On Sale</td>
            </tr>
          </thead>
          <tbody>
          ` +
      productTable +
      `
          </tbody>
        </table>
        <p>Total price:</p>
        <p>` +
      order["OrderCost"] +
      " SEK" +
      `</p>
        </div>
        <div class='order_overview__content__footer'>
        <button class='cancel-btn'>Close</button>  
        </div>
      </div>`;
    const hiddenOpenOrderInput = document.createElement("input");
    hiddenOpenOrderInput.setAttribute("type", "hidden");
    hiddenOpenOrderInput.setAttribute("name", "o_id");
    hiddenOpenOrderInput.setAttribute("value", order["OrderNumber"]);
    // formLink.appendChild(openOrderBtn);
    // formLink.appendChild(moodal);
    // formLink.appendChild(hiddenOpenOrderInput);
    // tdOpen.appendChild(formLink);
    tdOpen.appendChild(openOrderBtn);
    tdOpen.appendChild(moodal);
    tr.appendChild(tdId);
    tr.appendChild(tdCustomer);
    tr.appendChild(tdCity);
    tr.appendChild(tdDate);
    tr.appendChild(tdSum);
    tr.appendChild(tdStatus);
    orderType == "active"
      ? activeOrdersTable.appendChild(tr)
      : completedOrdersTable.appendChild(tr);
    tr.appendChild(tdOpen);
  });
  if (typeof openOrderBtn != "undefined") {
  }
  document.querySelectorAll(".open-modal").forEach((item) => {
    item.addEventListener("click", (event) => {
      let currentModal = event.currentTarget.nextElementSibling;
      currentModal.style.display = "block";
      window.onclick = function (event) {
        if (event.target == currentModal) {
          currentModal.style.display = "none";
        }
      };
      document.addEventListener("click", (e) => {
        if (e.target.className == "close") {
          currentModal.style.display = "none";
        }
      });
      document.addEventListener("click", (e) => {
        if (e.target.className == "cancel-btn") {
          currentModal.style.display = "none";
        }
      });
    });
  });
}
function updateStatus(orderToUpdate) {
  const statusSelect = document.getElementById("statusSelect" + orderToUpdate);
  const newStatusId = statusSelect.options[statusSelect.selectedIndex].value;
  const updateStatusForm = document.getElementById(
    "shouldUpdate" + orderToUpdate
  );
  const changeStatusModal = document.getElementById("changeStatusModal");
  const span = document.getElementsByClassName("close")[0];
  const cancelBtn = document.getElementById("cancel");
  const changeStatusBtn = document.getElementById("changeStatus");
  let shouldUpdate;
  if (newStatusId == "3") {
    changeStatusModal.style.display = "block";
    //close the changeStatusModal
    span.onclick = function () {
      changeStatusModal.style.display = "none";
      filterOrders(activeOrdersFromPHP);
    };
    //clicks anywhere outside of the changeStatusModal, close it
    window.onclick = function (event) {
      if (event.target == changeStatusModal) {
        changeStatusModal.style.display = "none";
        filterOrders(activeOrdersFromPHP);
      }
    };
    cancelBtn.addEventListener("click", (e) => {
      changeStatusModal.style.display = "none";
      shouldUpdate == false;
      filterOrders(activeOrdersFromPHP);
    });
    changeStatusBtn.addEventListener("click", (e) => {
      let pagePosition = window.pageYOffset;
      localStorage.setItem("page_position", pagePosition);
      changeStatusModal.style.display = "none";
      shouldUpdate == true;
      updateStatusForm.submit();
    });
  } else {
    // let thisRowId = this.event["target"].id;
    // localStorage.setItem("row", thisRowId);
    // let pagePosition = thisRow.getBoundingClientRect().top
    // localStorage.setItem("page_position", pagePosition);
    localStorage.setItem("page_position", window.pageYOffset);
    updateStatusForm.submit();
  }
}
