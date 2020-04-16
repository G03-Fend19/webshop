<?php

/**************************************
 * 
 * Filnamn: delete-product.php
 * Författare: Lova Duvnäs
 * Date: 2020-04-14
 * 
 * 1. Filen tar bort en rad från databasen
 *    med hjälp av ett id
 *************************************/

require_once '../../db.php';


if(isset($_GET['id'])){

  $id = htmlspecialchars($_GET['id']); 

  $sql = "DELETE FROM ws_products WHERE id = :id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
}

header('Location:../product-table.php');



//formulär type submit

//if server lika med post