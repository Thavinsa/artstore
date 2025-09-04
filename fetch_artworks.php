<?php
$conn = new mysqli("localhost","root","","artstore");
$artworks = $conn->query("SELECT id, title, artist_id, price, description FROM artworks");
echo '<table><tr><th>ID</th><th>Title</th><th>Artist ID</th><th>Price</th><th>Description</th><th>Action</th></tr>';
while($row = $artworks->fetch_assoc()){
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['artist_id']}</td>
        <td>{$row['price']}</td>
        <td>{$row['description']}</td>
        <td><button onclick='deleteArtwork({$row['id']})' class='delete-btn'>Delete</button></td>
    </tr>";
}
echo '</table>';
?>
