<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) exit;

$conn = new mysqli("localhost","root","","artstore");
if ($conn->connect_error) { die("Connection Failed: ".$conn->connect_error); }

$counts = [];

$customers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role='customer'");
$counts['customers'] = $customers->fetch_assoc()['count'];

$artworks = $conn->query("SELECT COUNT(*) AS count FROM artworks");
$counts['artworks'] = $artworks->fetch_assoc()['count'];

$orders = $conn->query("SELECT COUNT(*) AS count FROM orders");
$counts['orders'] = $orders->fetch_assoc()['count'];

echo json_encode($counts);
?>
