<?php
session_start();
$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] == 'customer'){
                header("Location: customer_dashboard.php");
            } elseif($row['role'] == 'artist'){
                header("Location: artist_dashboard.php");
            } else {
                header("Location: admin_dashboard.php");
            }
            exit;
        } else {
            echo "<p style='color:red;'>Invalid Password!</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found!</p>";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>
