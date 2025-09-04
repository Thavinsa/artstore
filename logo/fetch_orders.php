<?php
$conn = new mysqli("localhost","root","","artstore");
$orders = $conn->query("SELECT id, customer_id, artwork_id, quantity, total_price, status FROM orders");
echo '<table><tr><th>ID</th><th>Customer ID</th><th>Artwork ID</th><th>Qty</th><th>Total</th><th>Status</th><th>Action</th></tr>';
while($row = $orders->fetch_assoc()){
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['customer_id']}</td>
        <td>{$row['artwork_id']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['total_price']}</td>
        <td>{$row['status']}</td>
        <td><button onclick='deleteOrder({$row['id']})' class='delete-btn'>Delete</button></td>
    </tr>";
}
echo '</table>';
?>
