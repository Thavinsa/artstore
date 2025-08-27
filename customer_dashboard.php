<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer'){
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

// Place order
if(isset($_POST['order'])){
    $artwork_id = $_POST['artwork_id'];
    $customer_id = $_SESSION['user_id'];
    $artwork = $conn->query("SELECT price FROM artworks WHERE id='$artwork_id'")->fetch_assoc();
    $total_price = $artwork['price'];
    $conn->query("INSERT INTO orders (customer_id, artwork_id, total_price) VALUES ('$customer_id','$artwork_id','$total_price')");
    $message = "Order placed successfully!";
}

// Fetch artworks
$artworks = $conn->query("SELECT a.id, a.title, a.description, a.price, a.image_path, u.fullname as artist_name 
                          FROM artworks a JOIN users u ON a.artist_id = u.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard - ArtStore</title>
<style>
    /* Background Image */
    body {
        margin: 0;
        font-family: 'Arial', sans-serif;
        background-image: url('background.jpg'); /* Put your background image in the same folder */
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #fff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Transparent Container */
    .container {
        background: rgba(0, 0, 0, 0.6);
        padding: 30px;
        margin: 50px auto;
        max-width: 1200px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        flex: 1;
    }

    h2, h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #fff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    th {
        background: rgba(255,255,255,0.2);
    }

    tr:hover {
        background: rgba(255,255,255,0.1);
    }

    button {
        padding: 8px 15px;
        background: #4CAF50;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #45a049;
    }

    img.artwork {
        width: 100px;
        border-radius: 10px;
    }

    .message {
        text-align: center;
        margin: 15px 0;
        font-weight: bold;
        color: #00ff00;
    }

    /* Logout button fixed at bottom */
    .logout-container {
        text-align: center;
        margin-top: auto;
        padding: 20px;
        background: rgba(0,0,0,0.5);
    }

    .logout {
        display: inline-block;
        text-decoration: none;
        color: #fff;
        background: #ff4d4d;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .logout:hover {
        background: #e60000;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Welcome, Customer!</h2>
    
    <?php if(isset($message)){ echo "<div class='message'>$message</div>"; } ?>

    <h3>Available Artworks:</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Artist</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php while($row = $artworks->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['artist_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php if($row['image_path']){ ?><img src="<?php echo $row['image_path']; ?>" class="artwork"><?php } ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="artwork_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="order">Place Order</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<div class="logout-container">
    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
