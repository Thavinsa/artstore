<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'artist'){
    header("Location: login.php");
    exit;
}


$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

// Upload artwork
if(isset($_POST['upload'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $artist_id = $_SESSION['user_id'];
    $image_path = '';
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)){ mkdir($target_dir,0777,true); }
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }
    $conn->query("INSERT INTO artworks (artist_id,title,description,price,image_path) 
                 VALUES ('$artist_id','$title','$description','$price','$image_path')");
    echo "<p style='color:green; text-align:center;'>Artwork uploaded successfully!</p>";
}

// Fetch artist's artworks
$artworks = $conn->query("SELECT * FROM artworks WHERE artist_id=".$_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artist Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 50px auto 100px auto;
            background: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
        }
        h2, h3 {
            text-align: center;
            margin-top: 0;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, textarea, button {
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        button {
            background: #ff9800;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
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
    <h2>Welcome, Artist!</h2>

    <h3>Upload New Artwork</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="price" step="0.01" placeholder="Price" required>
        <input type="file" name="image">
        <button type="submit" name="upload">Upload</button>
    </form>

    <h3>My Artworks</h3>
    <table>
        <tr>
            <th>Title</th><th>Description</th><th>Price</th><th>Image</th>
        </tr>
        <?php while($row = $artworks->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
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
</div>

<a class="logout" href="logout.php">Logout</a>

</body>
</html>
