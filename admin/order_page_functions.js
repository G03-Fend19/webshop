function filterOrders(orders) {
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
  if (orders[1].OrderType == "active") {
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
    ordersToDraw = Object.values(ordersToDraw).filter(function(order) {
      return order["DeliveryCity"]
        .toLowerCase()
        .includes(filterText.toLowerCase());
    });
  }

  //If filterStatus is not 'all'
  if (filterStatus && filterStatus !== "all") {
    //filter ordersToDraw if chosen status matches order['OrderStatusId']
    ordersToDraw = Object.values(ordersToDraw).filter(function(order) {
      return order["OrderStatusId"].includes(filterStatus);
    });
  }

  Object.values(ordersToDraw).forEach(function (order, i) {
    const tr = document.createElement('tr');

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

      statusSelect.appendChild(statusOPending);
      statusSelect.appendChild(statusOInProgress);
      statusSelect.appendChild(statusOCompleted);
      statusForm.appendChild(statusSelect);
      statusForm.appendChild(hiddenStatusSelectInput);
      tdStatus.appendChild(statusForm);
    } else {
      tdStatus.innerHTML = order["OrderStatus"];
    }

    const tdOpen = document.createElement("td");
    const formLink = document.createElement("form");
    formLink.setAttribute("action", "");
    formLink.setAttribute("method", "POST");

    const openOrderBtn = document.createElement("button");
    openOrderBtn.setAttribute("type", "submit");
    openOrderBtn.innerHTML = "<i class='far fa-eye'></i>";

    const hiddenOpenOrderInput = document.createElement("input");
    hiddenOpenOrderInput.setAttribute("type", "hidden");
    hiddenOpenOrderInput.setAttribute("name", "o_id");
    hiddenOpenOrderInput.setAttribute("value", order["OrderNumber"]);

    formLink.appendChild(openOrderBtn);
    formLink.appendChild(hiddenOpenOrderInput);
    tdOpen.appendChild(formLink);

    tr.appendChild(tdId);
    tr.appendChild(tdCustomer);
    tr.appendChild(tdCity);
    tr.appendChild(tdDate);
    tr.appendChild(tdSum);
    tr.appendChild(tdStatus);
    orders[1].OrderType == "active"
      ? activeOrdersTable.appendChild(tr)
      : completedOrdersTable.appendChild(tr);
    tr.appendChild(tdOpen);
  });
}

function updateStatus(orderToUpdate) {
  const statusSelect = document.getElementById("statusSelect" + orderToUpdate);
  const newStatusId = statusSelect.options[statusSelect.selectedIndex].value;
  const updateStatusForm = document.getElementById(
    "shouldUpdate" + orderToUpdate
  );
  console.log(newStatusId);
  const modal = document.getElementById("myModal");
  const span = document.getElementsByClassName("close")[0];
  const cancelBtn = document.getElementById("cancel");
  const changeStatusBtn = document.getElementById("changeStatus");

  let shouldUpdate;
  if (newStatusId == "3") {
    modal.style.display = "block";
    //close the modal
    span.onclick = function() {
      modal.style.display = "none";
      filterOrders(activeOrdersFromPHP);
    };
    //clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
        filterOrders(activeOrdersFromPHP);
      }
    };
    cancelBtn.addEventListener("click", e => {
      modal.style.display = "none";
      shouldUpdate == false;
      filterOrders(activeOrdersFromPHP);
    });

    changeStatusBtn.addEventListener("click", e => {
      modal.style.display = "none";
      shouldUpdate == true;
      updateStatusForm.submit();
    });
  } else {
    updateStatusForm.submit();
  }
}
