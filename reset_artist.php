<?php
$conn = new mysqli("localhost","root","","artstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set a new password for an existing artist
$newPassword = password_hash("artist123", PASSWORD_DEFAULT);
$username = "artist1"; // replace with your artist's username

$sql = "UPDATE users SET password='$newPassword' WHERE username='$username'";
if ($conn->query($sql) === TRUE) {
    echo "Password reset successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>
