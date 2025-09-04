<?php
$conn = new mysqli("localhost","root","","artstore");
if(isset($_POST['id'])){
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
}
?>
