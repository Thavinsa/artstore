<?php
$conn = new mysqli("localhost", "root", "", "artstore");
if ($conn->connect_error) { die("DB failed"); }

function getCount($conn, $where) {
    $sql = "SELECT COUNT(*) AS cnt FROM $where";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['cnt'];
    }
    return 0;
}

echo json_encode([
    "customers" => getCount($conn, "users WHERE role='customer'"),
    "artists"   => getCount($conn, "users WHERE role='artist'"),
    "orders"    => getCount($conn, "orders"),
    "artworks"  => getCount($conn, "artworks")
]);
?>
