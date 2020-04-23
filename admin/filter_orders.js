function filterOrders(orders) {
  const statusFilter = document.querySelector('#filter-by-status');
  const textFilter = document.querySelector('#filter-by-text');
  const activeOrdersTable = document.querySelector('#activeOrdersTable')
  activeOrdersTable.innerHTML = '';

  let ordersToDraw = orders;

  const filterText = textFilter.value
  //If any input in text filter
  if (filterText) {
    //filter ordersToDraw if input matches orders.city
    ordersToDraw = ordersToDraw.filter(function (order) {
      return order['DeliveryCity'].toLowerCase().includes(filterText.toLowerCase())
    })
  }


  let filterStatus = statusFilter.options[statusFilter.selectedIndex].value;
  //If filterStatus is not 'all'
  if (filterStatus && filterStatus !== 'all') {
    //filter ordersToDraw if chosen status matches order['OrderStatusId']
    ordersToDraw = ordersToDraw.filter(function (order) {
      return order['OrderStatusId'].includes(filterStatus);
    })
  }

  ordersToDraw.forEach(function (order, i) {
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

    tr.appendChild(tdId)
    tr.appendChild(tdCustomer)
    tr.appendChild(tdCity)
    tr.appendChild(tdDate)
    tr.appendChild(tdSum)
    tr.appendChild(tdStatus)
    activeOrdersTable.appendChild(tr)
  })
}