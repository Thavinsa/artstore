
<?php
$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

if(isset($_POST['register'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer';

    // Check duplicate email or username
    $check = $conn->query("SELECT * FROM users WHERE email='$email' OR username='$username'");
    if($check->num_rows > 0){
        echo "Email or Username already exists!";
    } else {
        $conn->query("INSERT INTO users (fullname,email,username,password,role) VALUES ('$fullname','$email','$username','$password','$role')");
        echo "Registration Successful!";
    }
}
?>
<form method="POST">
    Full Name: <input type="text" name="fullname" required><br>
    Email: <input type="email" name="email" required><br>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="register">Register</button>
</form>
