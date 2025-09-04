<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: admin_dashboard.php?order_added=1");
        
    exit;
}

$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

if(isset($_POST['add_order'])){
    $customer_id = $_POST['customer_id'];
    $artwork_id = $_POST['artwork_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, artwork_id, quantity, total_price, status) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iiids", $customer_id, $artwork_id, $quantity, $total_price, $status);
    $stmt->execute();
    header("Location: admin_dashboard.php"); // Redirect back
    exit;
}

// Fetch existing users and artworks for dropdown
$users = $conn->query("SELECT id, username FROM users WHERE role='customer'");
$artworks = $conn->query("SELECT id, title FROM artworks");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Order</title>
    <style>
        body { font-family: Arial,sans-serif; background: #f0ebe3; }
        h2 { text-align:center; color: #8b5e3c; }
        form { max-width:400px; margin:auto; padding:20px; background: rgba(255,255,255,0.6); border-radius:12px; }
        input, select, button { width:100%; padding:8px; margin:5px 0; border-radius:6px; border:none; }
        button { background: rgba(178,140,111,0.5); color:#fff; cursor:pointer; }
        button:hover { background: rgba(178,140,111,0.8); }
    </style>
</head>
<body>

<h2>Place New Order</h2>
<form method="POST">
    <label>Customer:</label>
    <select name="customer_id" required>
        <?php while($u=$users->fetch_assoc()){ echo "<option value='{$u['id']}'>{$u['username']}</option>"; } ?>
    </select>

    <label>Artwork:</label>
    <select name="artwork_id" required>
        <?php while($a=$artworks->fetch_assoc()){ echo "<option value='{$a['id']}'>{$a['title']}</option>"; } ?>
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" value="1" required>

    <label>Total Price:</label>
    <input type="number" step="0.01" name="total_price" required>

    <label>Status:</label>
    <select name="status">
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select>

    <button type="submit" name="add_order">Place Order</button>
</form>
</body>
</html>
