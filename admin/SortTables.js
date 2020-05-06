function sortTable(n) {
  var table,
    rows,
    switching,
    i,
    x,
    y,
    shouldSwitch,
    dir,
    switchcount = 0,
    reverse;

  clickedTableId = this.event["target"].parentNode.parentNode.parentNode.id;
  if (clickedTableId == "activetable") {
    table = document.getElementById("activetable");
  } else {
    table = document.getElementById("completedtable");
  }

  switching = true;
  dir = "asc";
  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("td")[n];
      y = rows[i + 1].getElementsByTagName("td")[n];

      if (dir == "asc") {
        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount++;
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function sortTableDate(n) {
  var table,
    rows,
    switching,
    i,
    x,
    y,
    shouldSwitch,
    dir,
    switchcount = 0,
    reverse;
  clickedTableId = this.event["target"].parentNode.parentNode.parentNode.id;
  if (clickedTableId == "activetable") {
    table = document.getElementById("activetable");
  } else {
    table = document.getElementById("completedtable");
  }

  switching = true;
  dir = "asc";
  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];

      if (dir == "asc") {
        if (Date.parse(x.innerHTML) < Date.parse(y.innerHTML)) {
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (Date.parse(x.innerHTML) > Date.parse(y.innerHTML)) {
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount++;
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function sortTableStatus(n) {
  var table,
    rows,
    switching,
    i,
    x,
    y,
    shouldSwitch,
    dir,
    switchcount = 0,
    reverse;
  clickedTableId = this.event["target"].parentNode.parentNode.parentNode.id;
  if (clickedTableId == "activetable") {
    table = document.getElementById("activetable");
  } else {
    table = document.getElementById("completedtable");
  }

  switching = true;
  dir = "asc";
  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      // console.log(x)

      xForm = x.children[0][0];
      yForm = y.children[0][0];

      // console.log(parseInt(xForm.value))
      // console.log(parseInt(xForm.value))

      if (dir == "asc") {
        if (parseInt(xForm.value) > parseInt(yForm.value)) {
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (parseInt(xForm.value) < parseInt(yForm.value)) {
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount++;
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
