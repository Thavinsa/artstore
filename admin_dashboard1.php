<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Optional: Set default admin username if not set
$admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - ArtStore</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #8b5e3c, #d6a77a);
        min-height: 100vh;
        color: #333;
        text-align: center;
    }

    /* Header */
    .header {
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        position: relative;
    }
    .header h1 {
        margin: 0;
        font-size: 28px;
        color: #fff;
    }
    .header .logout-btn {
        background: rgba(255,80,80,0.7);
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        margin-left: 10px;
        transition: 0.3s;
    }
    .header .logout-btn:hover {
        background: rgba(255,80,80,1);
    }

    /* Dashboard boxes */
    .dashboard-boxes {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin: 50px 20px;
    }
    .box {
        background: rgba(255,255,255,0.3);
        width: 220px;
        padding: 30px 20px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        transition: 0.3s;
        text-align: center;
    }
    .box:hover {
        background: rgba(255,255,255,0.5);
    }
    .box i {
        font-size: 50px;
        color: #4a90e2;
        margin-bottom: 15px;
    }
    .box h2 {
        margin: 15px 0;
        font-size: 32px;
        color: #fff;
    }
    .box p {
        margin: 5px 0 15px;
        font-size: 18px;
        color: #fff;
    }
    .box a {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 6px;
        background: rgba(74,144,226,0.8);
        color: #fff;
        text-decoration: none;
        transition: 0.3s;
    }
    .box a:hover {
        background: rgba(74,144,226,1);
    }

    @media (max-width: 768px) {
        .dashboard-boxes {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
</head>
<body>

<div class="header">
    <h1>ArtStore Admin Dashboard</h1>
    <div>
        <span style="margin-right:15px; color:#fff;">Hello, <?= htmlspecialchars($admin_username) ?></span>
        <form action="logout.php" method="POST" style="display:inline;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="dashboard-boxes">
    <div class="box">
        <i class="fas fa-users"></i>
        <h2 id="customerCount">0</h2>
        <p>Customers</p>
        <a href="customers.php">Manage Customers</a>
    </div>
    <div class="box">
        <i class="fas fa-paint-brush"></i>
        <h2 id="artworkCount">0</h2>
        <p>Artworks</p>
        <a href="artworks.php">Manage Artworks</a>
    </div>
    <div class="box">
        <i class="fas fa-shopping-cart"></i>
        <h2 id="orderCount">0</h2>
        <p>Orders</p>
        <a href="orders.php">Manage Orders</a>
    </div>
</div>

<script>
function loadDashboardCounts() {
    fetch('get_dashboard_counts.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('customerCount').innerText = data.customers;
            document.getElementById('artworkCount').innerText = data.artworks;
            document.getElementById('orderCount').innerText = data.orders;
        })
        .catch(err => console.error(err));
}

// Load immediately and refresh every 5 seconds
loadDashboardCounts();
setInterval(loadDashboardCounts, 5000);
</script>

</body>
</html>
