<?php
$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error){ die("Connection Failed: ".$conn->connect_error); }

// Admin
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (fullname,email,username,password,role)
             VALUES ('Admin User','admin@example.com','Admin','$admin_pass','admin')");

// Artist
$artist_pass = password_hash('artist123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (fullname,email,username,password,role)
             VALUES ('Jane Artist','artist@example.com','Artist1','$artist_pass','artist')");

// Customer
$customer_pass = password_hash('customer123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (fullname,email,username,password,role)
             VALUES ('John Customer','customer@example.com','Customer1','$customer_pass','customer')");

echo "Users created successfully!";
?>
