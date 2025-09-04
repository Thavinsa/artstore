<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard1.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fixed admin credentials
    if ($username === "Admin" && $password === "admin123") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = "Admin";
        header("Location: admin_dashboard1.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - ArtStore</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #8b5e3c, #d6a77a);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-box {
        background: rgba(255,255,255,0.3);
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        text-align: center;
        width: 360px;
        backdrop-filter: blur(5px);
    }

    h1 {
        color: #fff;
        margin-bottom: 30px;
    }

    input {
        width: 80%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 6px;
        border: none;
        outline: none;
    }

    button {
        width: 85%;
        padding: 12px;
        margin-top: 15px;
        border-radius: 6px;
        border: none;
        background: rgba(255, 80, 80, 0.7);
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: rgba(255, 80, 80, 1);
    }

    .error-msg {
        color: #ff6961;
        margin-top: 10px;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="login-box">
    <h1><i class="fas fa-user-shield"></i> Admin Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        <?php if (!empty($error)) echo "<div class='error-msg'>$error</div>"; ?>
    </form>
</div>

</body>
</html>
