<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "artstore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, user_id, artwork_id, quantity, total_price FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Orders</title>
</head>
<body>
    <h2>All Orders</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Artwork ID</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['artwork_id']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['total_price']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No orders found</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
