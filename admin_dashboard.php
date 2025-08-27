<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

// Delete user
if(isset($_GET['delete_user'])){
    $id = $_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id='$id'");
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");

// Fetch artworks
$artworks = $conn->query("SELECT a.id, a.title, a.description, a.price, a.image_path, u.fullname as artist_name 
                          FROM artworks a JOIN users u ON a.artist_id = u.id");

// Fetch orders
$orders = $conn->query("SELECT o.id, c.fullname as customer_name, a.title as artwork_title, o.total_price, o.status, o.order_date
                        FROM orders o
                        JOIN users c ON o.customer_id = c.id
                        JOIN artworks a ON o.artwork_id = a.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('07.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto 100px auto;
            background: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
        }
        h2, h3 {
            text-align: center;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: rgba(255,255,255,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #fff;
            text-align: center;
        }
        a {
            color: #ff9800;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        img {
            border-radius: 5px;
        }
        .logout {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: red;
            color: #fff;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, Admin!</h2>

    <h3>Users</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>
        <?php while($row = $users->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td><a href="?delete_user=<?php echo $row['id']; ?>">Delete</a></td>
        </tr>
        <?php } ?>
    </table>

    <h3>Artworks</h3>
    <table>
        <tr><th>Title</th><th>Artist</th><th>Description</th><th>Price</th><th>Image</th></tr>
        <?php while($row = $artworks->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['artist_name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td>$<?php echo htmlspecialchars($row['price']); ?></td>
            <td>
                <?php if($row['image_path']){ ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" width="100">
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>Orders</h3>
    <table>
        <tr><th>ID</th><th>Customer</th><th>Artwork</th><th>Total</th><th>Status</th><th>Date</th></tr>
        <?php while($row = $orders->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($row['artwork_title']); ?></td>
            <td>$<?php echo htmlspecialchars($row['total_price']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<a class="logout" href="logout.php">Logout</a>

</body>
</html>
