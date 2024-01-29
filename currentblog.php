<?php
$conn = new mysqli("localhost", "root", "", "myBlog");

// Array to map category values to actual names
$categoryNames = array(
    "1" => "(1) Diary life",
    "2" => "(2) Celebrity",
    "3" => "(3) Radio drama",
    "4" => "(4) Gaming"
);


// to delete a posted blog
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $deleteQuery = "DELETE FROM postBlog2 WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);

    if ($deleteStmt === false) {
        die("Error in prepare statement: " . $conn->error);
    }

    $deleteStmt->bind_param("i", $id);

    if ($deleteStmt->execute() === true) {
    } else {
        echo "Error deleting record: " . $deleteStmt->error;
    }

    $deleteStmt->close();
}


$query = "SELECT * FROM postBlog2";

// check a category is specified in the search
if (!empty($_GET['category'])) {
    $category = $_GET['category'];
    $query .= " WHERE category = ?";
}

$result = $conn->prepare($query);

if (!empty($_GET['category'])) {
    $result->bind_param("s", $category);
}

$result->execute();
$result->store_result();
$result->bind_result($id, $title, $categoryValue, $content);

?>


  <!-- search blog by category -->
<?php
      $query = "SELECT * FROM postBlog2";

     if (!empty($_GET['category'])) {
       $category = $_GET['category'];
       $query .= " WHERE category = '$category'";
       }

     $result = $conn->query($query);
 ?>



<!-- html -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>homePage</title>
    <link rel="stylesheet" type="text/css" href="currentblog.css" />
</head>


<body>
    <div class="top"></div>
    <div id="main">
        <div id="toolbar">
            <p class="w">My Blogs</p>
            <ul id="toolbar-list">
                <p>
                    <li><a href="mainpage2.html">Home</a></li>
                    <li><a href="postblog.html">PostBlogs</a></li>
                    <li><a href="currentblog.php">CurrentBlogs</a></li>
                </p>
            </ul>
        </div>

        <h1></h1>
        <p class="profilephoto"><a href="login.html"><img src="loginHead.png" alt=""></a></p>
        <p id="bread">Your place：<strong>Current blogs</strong></p>


       
  <div class="search">
    <form method="get" action="currentblog.php">
        <input type="search" name="category" value="<?php if (!empty($_GET['category'])) { echo $_GET['category']; } ?>"
            class="text-word" placeholder="Input category NUMBER search related BLOGS">
            <input type="submit" value="🔍" class="buttonSearch">

        <input type="reset" value="Reload " class="buttonReload" onclick="window.location.href='currentblog.php';">
    </form>
  </div>

       

<!-- get the idea of printing the blog data in a table from online -->
        <?php

        echo "<table>";
        echo "<tr><th>Title</th><th>Category</th><th>Content</th><th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["title"] . "</td>";

            
            $categoryValue = $row["category"];
            $categoryName = isset($categoryNames[$categoryValue]) ? $categoryNames[$categoryValue] : "Unknown Category";

            echo "<td>" . $categoryName . "</td>";
            echo "<td>" . $row["content"] . "</td>";

            
            echo "<td>";
            echo "<form method='POST' action='currentblog.php'>"; 
            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            echo "<input type='submit' value='Delete'>";    //dalete
            echo "</form>";

            echo "<form method='GET' action='editBlog.php'>"; 
            echo "<input type='hidden' name='blog_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' value='Update'>";     //update
            echo "</form>";
            
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        $conn->close();
        ?>

    </div>
</body>

</html>
