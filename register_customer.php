<?php
// turn on mysqli exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$errors = [];
$success = "";

try {
    $conn = new mysqli("localhost","root","","artstore");
    $conn->set_charset("utf8mb4");

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Collect + validate
        $fullname = trim($_POST['fullname'] ?? "");
        $username = trim($_POST['username'] ?? "");
        $email    = trim($_POST['email'] ?? "");
        $password = $_POST['password'] ?? "";
        $confirm  = $_POST['confirm_password'] ?? "";

        if($fullname === "") $errors[] = "Full name is required.";
        if($username === "" || strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if(strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
        if($password !== $confirm) $errors[] = "Passwords do not match.";

        // Duplicate checks
        if(empty($errors)){
            // Check username
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0) $errors[] = "Username already exists.";
            $stmt->close();

            // Check email
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0) $errors[] = "Email already exists.";
            $stmt->close();
        }

        // Insert
        if(empty($errors)){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, 'customer')");
            $stmt->bind_param("ssss", $fullname, $email, $username, $hash);
            $stmt->execute();
            $stmt->close();

            $success = "Customer registered successfully. You can now log in.";
            // clear form
            $fullname = $username = $email = "";
        }
    }
} catch(Exception $e){
    $errors[] = "Unexpected error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Register Customer - ArtStore</title>
<style>
    body{
        margin:0;
        font-family: Arial, sans-serif;
        color:#fff;
        background:url('background.jpg') center/cover fixed no-repeat;
        min-height:100vh;
        display:flex;
        flex-direction:column;
    }
    .container{
        max-width:520px;
        width:90%;
        margin:60px auto 100px;
        background:rgba(0,0,0,0.6);
        padding:24px;
        border-radius:14px;
        box-shadow:0 8px 20px rgba(0,0,0,0.45);
    }
    h2{ text-align:center; margin-top:0; }
    .msg{ margin:12px 0; padding:10px 12px; border-radius:8px; }
    .msg.error{ background:rgba(255,0,0,0.25); border:1px solid rgba(255,0,0,0.5); }
    .msg.success{ background:rgba(0,128,0,0.25); border:1px solid rgba(0,128,0,0.5); }
    form{ display:flex; flex-direction:column; gap:12px; }
    input{
        padding:12px;
        border:none;
        border-radius:8px;
        background:rgba(255,255,255,0.15);
        color:#fff;
        outline:none;
    }
    input::placeholder{ color:rgba(255,255,255,0.8); }
    button{
        padding:12px;
        border:none;
        border-radius:10px;
        cursor:pointer;
        background:#4CAF50;
        color:#fff;
        font-weight:bold;
        transition:0.25s;
    }
    button:hover{ transform:translateY(-1px); }
    .bottom-links{
        text-align:center;
        margin-top:10px;
    }
    .link{
        color:#ffd27f; text-decoration:none; font-weight:bold;
    }
    .link:hover{ text-decoration:underline; }
    .footer{
        margin-top:auto;
        padding:18px;
        text-align:center;
        background:rgba(0,0,0,0.5);
    }
</style>
</head>
<body>

<div class="container">
    <h2>Create Customer Account</h2>

    <?php if(!empty($errors)): ?>
        <div class="msg error">
            <?php foreach($errors as $er) echo htmlspecialchars($er)."<br>"; ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <input type="text" name="fullname" placeholder="Full name" value="<?php echo htmlspecialchars($fullname ?? ""); ?>" required>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? ""); ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ""); ?>" required>
        <input type="password" name="password" placeholder="Password (min 6 chars)" required>
        <input type="password" name="confirm_password" placeholder="Confirm password" required>
        <button type="submit">Register</button>
    </form>

    <div class="bottom-links">
        <a class="link" href="login.php">Back to Login</a>
    </div>
</div>

<div class="footer">
    © ArtStore
</div>

</body>
</html>
