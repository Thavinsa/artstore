<?php
$conn = new mysqli("localhost","root","","artstore");
$pass = password_hash("artist123", PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (username,password,role) VALUES ('artist1','$pass','artist')");
echo "Artist user created!";
?>
