<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost","root","","artstore");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Add new artwork
if (isset($_POST['add_artwork'])) {
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO artworks (title, artist_id, price, description) VALUES (?,?,?,?)");
    $stmt->bind_param("sids", $title, $artist_id, $price, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Artwork added successfully!'); window.location='artworks.php';</script>";
    } else {
        echo "Error adding artwork: " . $stmt->error;
    }
}

// Delete artwork
if (isset($_POST['delete_artwork'])) {
    $id = $_POST['artwork_id'];
    $stmt = $conn->prepare("DELETE FROM artworks WHERE id=?");
    $stmt->bind_param("i",$id);
    if ($stmt->execute()) {
        echo "<script>alert('Artwork deleted successfully!'); window.location='artworks.php';</script>";
    } else {
        echo "Error deleting artwork: " . $stmt->error;
    }
}

// Fetch all artworks
$artworks = $conn->query("SELECT id, title, artist_id, price, description FROM artworks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Artworks - ArtStore</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin:0; padding:0;
    background: linear-gradient(135deg, #8b5e3c, #d6a77a); /* same as dashboard */
    min-height:100vh; color:#333; text-align:center;
}

/* Header */
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

/* Form card */
.form-card {
    background: rgba(255,255,255,0.3); width:400px; margin:30px auto;
    padding:25px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2);
    backdrop-filter: blur(5px);
}
.form-card input, .form-card input[type="number"] {
    width: calc(100% - 20px); padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc;
}
.form-card button {
    padding:10px 20px; border:none; border-radius:6px; background:#3498db; color:#fff; cursor:pointer; transition:0.3s;
}
.form-card button:hover { background:#2980b9; }

/* Table */
table {
    width:90%; margin:20px auto;
    border-collapse: collapse;
    background: rgba(255,255,255,0.3);
    border-radius:12px;
    overflow:hidden;
    backdrop-filter: blur(5px);
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
    <h1>Manage Artworks</h1>
    <a href="admin_dashboard1.php"><button class="btn">Back to Dashboard</button></a>
    <a href="logout.php"><button class="btn">Logout</button></a>
</div>

<div class="form-card">
<form method="POST">
    <input type="text" name="title" placeholder="Artwork Title" required>
    <input type="number" name="artist_id" placeholder="Artist ID" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="text" name="description" placeholder="Description">
    <button type="submit" name="add_artwork">Add Artwork</button>
</form>
</div>

<table>
<tr><th>ID</th><th>Title</th><th>Artist ID</th><th>Price</th><th>Description</th><th>Action</th></tr>
<?php while($row = $artworks->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['title'] ?></td>
    <td><?= $row['artist_id'] ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['description'] ?></td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="artwork_id" value="<?= $row['id'] ?>">
            <button type="submit" name="delete_artwork" class="delete-btn">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
