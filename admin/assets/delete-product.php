<?php

require_once '../../db.php';

function deleteConfirm() {
  if (confirm("Are you sure you want to delete this product?")) {
    return true;
  } else {
    return false;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  $id = htmlspecialchars($_POST['id']);

  $sql = 'DELETE FROM ws_products WHERE id = :id';

  $stmt = $db->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

}

header('Location:../products_page.php');



