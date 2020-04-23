function filterOrders(orders) {
  //Active orders filter select/input element
  const activeStatusFilter = document.querySelector('#activeStatusFilter');
  const activeTextFilter = document.querySelector('#activeTextFilter');

  //Completed orders filter input element
  const completedTextFilter = document.querySelector('#completedTextFilter');

  //Active/Completed orders tables
  const activeOrdersTable = document.querySelector('#activeOrdersTable')
  const completedOrdersTable = document.querySelector('#completedOrdersTable')

  let ordersToDraw = orders;
  let filterText;
  let filterStatus;

  //Checks if list of orders is active orders
  if (orders[1].OrderType == "active") {
    activeOrdersTable.innerHTML = '';
    //Sets filter values of select/input
    filterText = activeTextFilter.value
    filterStatus = activeStatusFilter.options[activeStatusFilter.selectedIndex].value;
  } else {
    completedOrdersTable.innerHTML = '';
    //Sets filter value of input
    filterText = completedTextFilter.value
  }
  //If input in any text filter
  if (filterText) {
    //filter ordersToDraw if input matches orders.city
    ordersToDraw = Object.values(ordersToDraw).filter(function (order) {
      return order['DeliveryCity'].toLowerCase().includes(filterText.toLowerCase())
    })
  }


  //If filterStatus is not 'all'
  if (filterStatus && filterStatus !== 'all') {
    //filter ordersToDraw if chosen status matches order['OrderStatusId']
    ordersToDraw = Object.values(ordersToDraw).filter(function (order) {
      return order['OrderStatusId'].includes(filterStatus);
    })
  }

  Object.values(ordersToDraw).forEach(function (order, i) {
    console.log(order)
    const tr = document.createElement('tr');

    const tdId = document.createElement('td');
    tdId.innerHTML = "#" + order['OrderNumber'];

    const tdCustomer = document.createElement('td')
    let fullName = order['CustomerFirstName'] + " " + order['CustomerLastName']
    const fullNameShort = fullName.substring(0, 20) + "...";
    fullName.length > 20 ? fullName = fullNameShort : fullName = fullName
    tdCustomer.innerHTML = fullName;

    const tdCity = document.createElement('td')
    tdCity.innerHTML = order['DeliveryCity']

    const tdDate = document.createElement('td')
    tdDate.innerHTML = order['OrderDate']

    const tdSum = document.createElement('td')
    tdSum.innerHTML = order['OrderCost']

    const tdStatus = document.createElement('td')
    if (order.OrderType == "active") {
      const statusSelect = document.createElement('select')

      const statusOPending = document.createElement('option')
      statusOPending.innerHTML = "Pending";
      statusOPending.setAttribute("value", "1")
      if (order['OrderStatusId'] == statusOPending.value) {
        statusOPending.setAttribute("selected", "selected")
      }
      const statusOInProgress = document.createElement('option')
      statusOInProgress.innerHTML = "In progress";
      statusOInProgress.setAttribute("value", "2")
      if (order['OrderStatusId'] == statusOInProgress.value) {
        statusOInProgress.setAttribute("selected", "selected")
      }
      const statusOCompleted = document.createElement('option')
      statusOCompleted.innerHTML = "Completed";
      statusOCompleted.setAttribute("value", "3")
      if (order['OrderStatusId'] == statusOCompleted.value) {
        statusOCompleted.setAttribute("selected", "selected")
      }

      statusSelect.appendChild(statusOPending);
      statusSelect.appendChild(statusOInProgress);
      statusSelect.appendChild(statusOCompleted);
      tdStatus.appendChild(statusSelect);
    } else {
      tdStatus.innerHTML = order['OrderStatus']
    }

    const tdOpen = document.createElement('td')
    const formLink = document.createElement('form')
    formLink.setAttribute("action", "")
    formLink.setAttribute("method", "POST")

    const openBtn = document.createElement('button')
    openBtn.setAttribute("type", "submit")
    openBtn.innerHTML = "<i class='far fa-eye'></i>"

    const hiddenInput = document.createElement('input')
    hiddenInput.setAttribute("type", "hidden")
    hiddenInput.setAttribute("name", "o_id")
    hiddenInput.setAttribute("value", order['OrderNumber'])

    formLink.appendChild(openBtn)
    formLink.appendChild(hiddenInput)
    tdOpen.appendChild(formLink)




    tr.appendChild(tdId)
    tr.appendChild(tdCustomer)
    tr.appendChild(tdCity)
    tr.appendChild(tdDate)
    tr.appendChild(tdSum)
    tr.appendChild(tdStatus)
    orders[1].OrderType == "active" ? activeOrdersTable.appendChild(tr) : completedOrdersTable.appendChild(tr)
    tr.appendChild(tdOpen)
  })
}
