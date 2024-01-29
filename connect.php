<?php
$conn = new mysqli("localhost", "root", "", "myBlog");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $_POST["title"];
$category = $_POST["category"];
$content = $_POST["content"];
$blog_id = $_POST["blog_id"];  

if (!empty($blog_id)) {
    // Update the original data to blog 
    $query = $conn->prepare("UPDATE postBlog2 SET title=?, category=?, content=? WHERE id=?");
    $query->bind_param("sssi", $title, $category, $content, $blog_id);
} else {
    // Insert new data to blog
    $query = $conn->prepare("INSERT INTO postBlog2 (title, category, content) VALUES (?, ?, ?)");
    $query->bind_param("sss", $title, $category, $content);
}

if (!$query->execute()) {
    die("Error: " . $query->error);
}


header("Location: currentblog.php");
exit();

?>
