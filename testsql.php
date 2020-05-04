<?php
  require_once "./db.php";
  $firstName = 'Farid';
  $lastName = 'Fakhouri';
  $email = 'bossdog@gmail.com';
  $telNr = '5687898';

  $street = "Bullgatan 5";
  $postalCode = "78968";
  $city = "Åkerstyckebruk";

  $customerId;
  $addressId;

  //Try to fetch data from database where email from form matches
  $getCustomerSQL = "SELECT
                      ws_customers.id         AS CustomerId,
                      ws_customers.first_name AS FirstName,
                      ws_customers.last_name  AS LastName,
                      ws_customers.email      AS Email,
                      ws_customers.tel_nr     AS Phone
                    FROM 
                      ws_customers
                    WHERE
                      ws_customers.email = :email";
  
  $getCustomerStmt = $db->prepare($getCustomerSQL);
  $getCustomerStmt->bindParam(':email', $email);
  $getCustomerStmt->execute();

  $customerResults = $getCustomerStmt->fetch(PDO::FETCH_ASSOC);

  //If there was no match in database
  if(empty($customerResults)) {
    //Create a new customer in database
    $createCustomerSQL = "INSERT INTO ws_customers (first_name,last_name, email, tel_nr)
                          VALUES (:first_name, :last_name, :email, :tel_nr)";
    $createCustomerStmt = $db->prepare($createCustomerSQL);
    $createCustomerStmt->bindParam(":first_name", $firstName);
    $createCustomerStmt->bindParam(":last_name", $lastName);
    $createCustomerStmt->bindParam(":email", $email);
    $createCustomerStmt->bindParam(":tel_nr", $telNr);
    $createCustomerStmt->execute();

    //and get the new customer from database to get access to auto incremented customer id
    $getNewCustomerSQL = $getCustomerSQL;
    $getNewCustomerStmt = $db->prepare($getNewCustomerSQL);
    $getNewCustomerStmt->bindParam(':email', $email);
    $getNewCustomerStmt->execute();

    $newCustomerResults = $getNewCustomerStmt->fetch(PDO::FETCH_ASSOC);
    //Set customer id
    $customerId = $newCustomerResults['CustomerId'];
    echo $customerId;
    echo "<br>";
    echo "New customer $customerId now exists and can be billed";
    echo "<br>";
  }
  //If there was a match on email and all other data matches
  elseif ($customerResults['FirstName'] == $firstName &&
          $customerResults['LastName'] == $lastName &&
          $customerResults['Email'] == $email &&
          $customerResults['Phone'] == $telNr) {
    //Set customer Id
    $customerId = $customerResults['CustomerId'];
    echo $customerId;
    echo "<br>";
    echo "Customer $customerId exists and can be billed";
    echo "<br>";
    //Try to fetch address from database where address inputs from form matches
    $getAddressSQL = "SELECT
                        ws_delivery_address.id           AS AddressId,
                        ws_delivery_address.street       AS Street,
                        ws_delivery_address.postal_code  AS PostalCode,
                        ws_delivery_address.city         AS City
                      FROM 
                        ws_delivery_address
                      WHERE
                        ws_delivery_address.street = :street
                      AND
                        ws_delivery_address.postal_code = :postal_code
                      AND
                        ws_delivery_address.city = :city";
    
    $getAddressStmt = $db->prepare($getAddressSQL);
    $getAddressStmt->bindParam(':street', $street);
    $getAddressStmt->bindParam(':postal_code', $postalCode);
    $getAddressStmt->bindParam(':city', $city);
    $getAddressStmt->execute();
  
    $addressResults = $getAddressStmt->fetch(PDO::FETCH_ASSOC);
  
    if(empty($addressResults)) {
      //Create a new customer in database
      $createAddressSQL = "INSERT INTO ws_delivery_address (street, postal_code, city)
                            VALUES (:street, :postal_code, :city)";
      $createAddressStmt = $db->prepare($createAddressSQL);
      $createAddressStmt->bindParam(":street", $street);
      $createAddressStmt->bindParam(":postal_code", $postalCode);
      $createAddressStmt->bindParam(":city", $city);
      $createAddressStmt->execute();
  
      //and get the new customer from database to get access to auto incremented customer id
      $getNewAddressSQL = $getAddressSQL;
      $getNewAddressStmt = $db->prepare($getNewAddressSQL);
      $getNewAddressStmt->bindParam(":street", $street);
      $getNewAddressStmt->bindParam(":postal_code", $postalCode);
      $getNewAddressStmt->bindParam(":city", $city);
      $getNewAddressStmt->execute();
  
      $newAddressResults = $getNewAddressStmt->fetch(PDO::FETCH_ASSOC);
      //Set address id
      $addressId = $newAddressResults['AddressId'];
      echo $addressId;
      echo "<br>";
      echo "New address $addressId now exists and can be billed";
      echo "<br>";
    } else {
      $addressId = $addressResults['AddressId'];
      echo "$addressId exists";
      echo "<br>";
      echo $addressResults['Street'];
      echo "<br>";
      echo $addressResults['PostalCode'];
      echo "<br>";
      echo $addressResults['City'];
    }
  } else {
    // If email matched but not the rest, display error
    echo "Email already registered on a different name. Please check spelling or use a different email address";
  }

  if (isset($customerId) && isset($addressId)) {
    
  }
?>