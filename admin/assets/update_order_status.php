<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once "../../db.php";
  $orderId = $_POST['o_id'];
  $newStatusId = $_POST['statusSelect' . $orderId];
  $returnUrl = $_POST['returnUrl'];

  if ($newStatusId !== "3") {
    $sql = "UPDATE ws_active_orders
            SET status_id = :new_status_id
            WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":new_status_id", $newStatusId);
    $stmt->bindParam(":id", $orderId);
    $stmt->execute();
  } else {

    $getActiveOrderSql = "SELECT * FROM ws_active_orders WHERE ws_active_orders.id = :orderId";
    $getActiveOrderStmt = $db->prepare($getActiveOrderSql);
    $getActiveOrderStmt->bindParam(":orderId", $orderId);
    $getActiveOrderStmt->execute();

    $activeOrder = $getActiveOrderStmt->fetch(PDO::FETCH_ASSOC);
    $activeOrderId = $activeOrder['id'];
    $activeOrderCustomerId = $activeOrder['customer_id'];
    $activeOrderDeliverAddressId = $activeOrder['delivery_address_id'];
    $activeOrderOrderDate = $activeOrder['order_date'];
    $activeOrderTotalCost = $activeOrder['total_cost'];
    $activeOrderShippingId = $activeOrder['shipping_id'];

    $createCompletedOrderSql = "INSERT INTO
                                  ws_completed_orders
                                  (id, customer_id, delivery_address_id, order_date, total_cost, shipping_id, status_id)
                                VALUES
                                  (:id, :customer_id, :delivery_address_id, :order_date, :total_cost, :shipping_id, :status_id)";
    $createCompletedOrderStmt = $db->prepare($createCompletedOrderSql);
    $createCompletedOrderStmt->bindParam(":id", $activeOrderId);
    $createCompletedOrderStmt->bindParam(":customer_id", $activeOrderCustomerId);
    $createCompletedOrderStmt->bindParam(":delivery_address_id", $activeOrderDeliverAddressId);
    $createCompletedOrderStmt->bindParam(":order_date", $activeOrderOrderDate);
    $createCompletedOrderStmt->bindParam(":total_cost", $activeOrderTotalCost);
    $createCompletedOrderStmt->bindParam(":shipping_id", $activeOrderShippingId);
    $createCompletedOrderStmt->bindParam(":status_id", $newStatusId);
    $createCompletedOrderStmt->execute();

    $deleteActiveOrderSql = "DELETE FROM ws_active_orders WHERE ws_active_orders.id = :orderId";
    $deleteActiveOrderStmt = $db->prepare($deleteActiveOrderSql);
    $deleteActiveOrderStmt->bindParam(":orderId", $orderId);
    $deleteActiveOrderStmt->execute();
  }
}
header("Location:" . $returnUrl);

// if(isset($_GET['order_id']))
// $sql = "UPDATE ws_active_orders SET "
?>