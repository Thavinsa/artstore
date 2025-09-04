<?php
$conn = new mysqli("localhost","root","","artstore");
$users = $conn->query("SELECT id, username, email, role FROM users");
echo '<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>';
while($row = $users->fetch_assoc()){
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['email']}</td>
        <td>{$row['role']}</td>
        <td><button onclick='deleteUser({$row['id']})' class='delete-btn'>Delete</button></td>
    </tr>";
}
echo '</table>';
?>
