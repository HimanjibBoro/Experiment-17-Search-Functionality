<form method="GET" action="search.php">
    <input type="text" name="q" placeholder="Search posts..." required>
    <button type="submit">Search</button>
</form>
<hr>

<?php
require 'db.php';

$search = isset($_GET['q']) ? $_GET['q'] : "";

if (!empty($search)) {
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.author_id = users.id
            WHERE posts.title LIKE ? OR posts.content LIKE ?
            ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $param = "%$search%";  
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.author_id = users.id
            ORDER BY created_at DESC";

    $result = $conn->query($sql);
}
?>

<h2>Search Results</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['content'] . "</p>";
        echo "<small>Author: " . $row['username'] . " | Date: " . $row['created_at'] . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>No results found.</p>";
}
?>
