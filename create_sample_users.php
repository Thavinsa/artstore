<?php
$conn = new mysqli("localhost","root","","artstore");
if($conn->connect_error) { die("Connection Failed: ".$conn->connect_error); }

// Sample users
$users = [
    [
        "fullname" => "Admin User",
        "username" => "admin",
        "email" => "admin@example.com",
        "password" => "admin123",
        "role" => "admin"
    ],
    [
        "fullname" => "John Doe",
        "username" => "artist1",
        "email" => "artist1@example.com",
        "password" => "artist123",
        "role" => "artist"
    ],
    [
        "fullname" => "Jane Smith",
        "username" => "customer1",
        "email" => "customer1@example.com",
        "password" => "customer123",
        "role" => "customer"
    ]
];

foreach($users as $user){
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $user['fullname'], $user['username'], $user['email'], $hashed_password, $user['role']);
    $stmt->execute();
}

echo "Sample users added successfully!";
?>
