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

$sql = "SELECT id, title, artist_id, price FROM artworks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Artworks</title>
</head>
<body>
    <h2>All Artworks</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Artist ID</th>
            <th>Price</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['artist_id']}</td>
                        <td>{$row['price']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No artworks found</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
