<?php
// get_dashboard_counts.php
// Output strict JSON without stray warnings

// IMPORTANT: prevent PHP notices/warnings from breaking JSON
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "artstore";

$conn = @new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'DB connection failed: ' . $conn->connect_error
    ]);
    exit;
}

/*
 * Adjust these queries to match your schema:
 * - If *customers are stored in `users`* with a `role` column, keep WHERE role='customer'.
 * - If you have a separate `customers` table, change the first query to: SELECT COUNT(*) FROM customers
 */
$customersCount = 0;
$artworksCount  = 0;
$ordersCount    = 0;

if ($res = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='customer'")) {
    $row = $res->fetch_assoc();
    $customersCount = (int)$row['c'];
    $res->free();
} else {
    echo json_encode(['error' => 'Query failed: users']); exit;
}

if ($res = $conn->query("SELECT COUNT(*) AS c FROM artworks")) {
    $row = $res->fetch_assoc();
    $artworksCount = (int)$row['c'];
    $res->free();
} else {
    echo json_encode(['error' => 'Query failed: artworks']); exit;
}

if ($res = $conn->query("SELECT COUNT(*) AS c FROM orders")) {
    $row = $res->fetch_assoc();
    $ordersCount = (int)$row['c'];
    $res->free();
} else {
    echo json_encode(['error' => 'Query failed: orders']); exit;
}

echo json_encode([
    'customers' => $customersCount,
    'artworks'  => $artworksCount,
    'orders'    => $ordersCount
]);

$conn->close();
