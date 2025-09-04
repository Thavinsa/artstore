<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost","root","","artstore");
if ($conn->connect_error) { die("Connection Failed: ".$conn->connect_error); }

// Add new order
if (isset($_POST['add_order'])) {
    $customer_id = $_POST['customer_id'];
    $artwork_id = $_POST['artwork_id'];
    $quantity = $_POST['quantity'];
    $status = 'Pending'; // Default status

    // Get artwork price
    $artwork_res = $conn->query("SELECT price FROM artworks WHERE id=$artwork_id");
    $artwork = $artwork_res->fetch_assoc();
    $total_price = $artwork['price'] * $quantity;

    // Insert including total_price and status
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, artwork_id, quantity, total_price, status) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iiids", $customer_id, $artwork_id, $quantity, $total_price, $status);
    if ($stmt->execute()) {
        header("Location: orders.php");
        exit;
    } else {
        echo "Error adding order: " . $stmt->error;
    }
}

// Delete order
if (isset($_POST['delete_order'])) {
    $id = $_POST['order_id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

// Fetch all orders
$orders = $conn->query("
    SELECT o.id, u.username AS customer, a.title AS artwork, o.quantity, o.total_price, o.status
    FROM orders o
    JOIN users u ON o.customer_id = u.id
    JOIN artworks a ON o.artwork_id = a.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders - ArtStore</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin:0; padding:0;
    background: linear-gradient(135deg, #8b5e3c, #d6a77a);
    min-height:100vh; color:#333; text-align:center;
}
.header {
    background: rgba(0,0,0,0.6);
    color:#fff;
    padding:20px 40px;
    display:flex;
    justify-content:flex-end;
    align-items:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.3);
}
.header h1 {
    position:absolute; left:40px; margin:0; font-size:28px; color:#fff;
}
.header .btn {
    background: rgba(255,80,80,0.7); border:none; padding:10px 18px;
    border-radius:6px; color:#fff; cursor:pointer; margin-left:10px; transition:0.3s;
}
.header .btn:hover { background: rgba(255,80,80,1); }

.form-card {
    background: rgba(255,255,255,0.3); width:400px; margin:30px auto;
    padding:25px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2);
    backdrop-filter: blur(5px);
}
.form-card select, .form-card input {
    width: calc(100% - 20px); padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc;
}
.form-card button {
    padding:10px 20px; border:none; border-radius:6px; background:#3498db; color:#fff; cursor:pointer; transition:0.3s;
}
.form-card button:hover { background:#2980b9; }

table {
    width:90%; margin:20px auto; border-collapse: collapse;
    background: rgba(255,255,255,0.3); border-radius:12px; overflow:hidden; backdrop-filter: blur(5px);
}
th, td { padding:12px; border-bottom:1px solid rgba(0,0,0,0.2); text-align:center; }
th { background: rgba(74,144,226,0.8); color:#fff; }
.delete-btn {
    background: rgba(255,80,80,0.7); color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;
    transition:0.3s;
}
.delete-btn:hover { background: rgba(255,80,80,1); }

@media (max-width:480px){ .form-card, table { width:95%; } }
</style>
</head>
<body>

<div class="header">
    <h1>Manage Orders</h1>
    <a href="admin_dashboard1.php"><button class="btn">Back to Dashboard</button></a>
    <a href="logout.php"><button class="btn">Logout</button></a>
</div>

<div class="form-card">
<form method="POST">
    <select name="customer_id" required>
        <option value="">Select Customer</option>
        <?php
        $customers = $conn->query("SELECT id, username FROM users WHERE role='customer'");
        while($c = $customers->fetch_assoc()){
            echo "<option value='".$c['id']."'>".$c['username']."</option>";
        }
        ?>
    </select>
    <select name="artwork_id" required>
        <option value="">Select Artwork</option>
        <?php
        $arts = $conn->query("SELECT id, title FROM artworks");
        while($a = $arts->fetch_assoc()){
            echo "<option value='".$a['id']."'>".$a['title']."</option>";
        }
        ?>
    </select>
    <input type="number" name="quantity" placeholder="Quantity" required min="1">
    <button type="submit" name="add_order">Add Order</button>
</form>
</div>

<table>
<tr><th>ID</th><th>Customer</th><th>Artwork</th><th>Quantity</th><th>Total Price</th><th>Status</th><th>Action</th></tr>
<?php while($row = $orders->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['customer'] ?></td>
    <td><?= $row['artwork'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['total_price'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
            <button type="submit" name="delete_order" class="delete-btn">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
