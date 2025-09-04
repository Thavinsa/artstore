<?php
// fetch_dashboard_stats.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "artstore";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customers = $conn->query("SELECT COUNT(*) as c FROM customers")->fetch_assoc()['c'];
$artworks  = $conn->query("SELECT COUNT(*) as c FROM artworks")->fetch_assoc()['c'];
$orders    = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];

echo json_encode([
    'customers' => $customers,
    'artworks' => $artworks,
    'orders' => $orders
]);

$conn->close();
?>
