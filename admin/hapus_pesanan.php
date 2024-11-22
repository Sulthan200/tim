<?php
require 'db_connect.php';

$order_id = $_GET['id'];
$query = "DELETE FROM orders WHERE id = $order_id";

if (mysqli_query($conn, $query)) {
    echo "Order deleted successfully.";
} else {
    echo "Error deleting order: " . mysqli_error($conn);
}

header('Location: orders.php');
exit;
?>
