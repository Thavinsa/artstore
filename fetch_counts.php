<?php
include 'db.php';

$counts = ['customers'=>0,'artworks'=>0,'orders'=>0];

$cust = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='customer'");
$art = $conn->query("SELECT COUNT(*) AS c FROM artworks");
$ord = $conn->query("SELECT COUNT(*) AS c FROM orders");

if($cust && $row = $cust->fetch_assoc()) $counts['customers'] = $row['c'];
if($art && $row = $art->fetch_assoc()) $counts['artworks'] = $row['c'];
if($ord && $row = $ord->fetch_assoc()) $counts['orders'] = $row['c'];

echo json_encode($counts);
?>
