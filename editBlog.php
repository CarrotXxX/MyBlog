<?php
$conn = new mysqli("localhost", "root", "", "myBlog");

// Array to map category values to actual names
$categoryNames = array(
    "1" => "Diary life",
    "2" => "Celebrity",
    "3" => "Radio drama",
    "4" => "Gaming"
);

// Check if receive message
$id = "";
$title = "";
$category = "";
$content = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    // GET method: show the data of the blog
    if (!isset($_GET["blog_id"])) {
        header("Location: currentblog.html");
        exit;
    }

    $id = $_GET["blog_id"];

    // Read the row of the selected blog from the database table
    $sql = "SELECT * FROM postBlog2 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: currentblog.html");
        exit;
    }

    $title = $row["title"];
    $category = $row["category"];
    $content = $row["content"];

} else {
       // POST method: update the data of the blog
      $id = $_POST["blog_id"];
      $title = $_POST["title"];
      $category = $_POST["category"];
      $content = $_POST["content"];

    do {  //get this idea from online 
        if (empty($id) || empty($title) || empty($category) || empty($content)) {
            $errorMessage = "All fields are required";
            break;
        }

        if (!$stmt) {
            $errorMessage = "Invalid query:" . $conn->error;
            break;
        }

        $stmt = $conn->prepare("UPDATE postBlog2 SET title=?, category=?, content=? WHERE blog_id=?");
        $stmt->bind_param("sssi", $title, $category, $content, $id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $errorMessage = "No rows updated. Blog may not exist.";
            break;
        }

        $successMessage = "Blog updated successfully";
        $stmt->close();

    } while (false);
}

?>



<!-- html -->
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>homePage</title>
    <link rel="stylesheet" type="text/css" href="postblog.css"/>
    
</head>

<body>
    <div class="top"></div>
    <div id="main">
        <div id="toolbar">
            <p class="w">Update a blog here!</p>
            <ul id="toolbar-list">
                <p>
                    <li><a href="mainpage2.html">Home</a></li>
                    <li><a href="postblog.html">PostBlogs</a></li>
                    <li><a href="currentblog.php">CurrentBlogs</a></li>
                </p>
            </ul>
        </div>

        <!-- post a blog box -->
        <div class="box">
            <h1>Update a blog...</h1>
 
        
            <form method="post" action="connect.php">
               <input type="hidden" name="blog_id" value="<?php echo $id; ?>">

              <table class="postblog-table">
                    <tr><th>Title</th><td><input type="text" placeholder="Title" name="title" value="<?php echo $title;?>" ></td></tr>
                    <tr><th>Category</th><td>

                     <select name="category" required class="select">
                        <option value="">Please choose</option>

                       <?php  
                          foreach ($categoryNames as $value => $name) { //get this idea from online 
                          $selected = ($value == $category) ? 'selected' : '';
                         echo "<option value=\"$value\" $selected>$name</option>"; }
                        ?>
                     </select>

                    </td></tr>
                    <tr><th>Content</th><td><textarea type="textarea" name="content" placeholder="Please input content here" required rows="7" cols="80"><?php echo $content;?></textarea></td></tr>
                    <tr> <td colspan="3" class="td-btn">
                <input type="submit" value="Submit" class="button" />
                <input type="reset" value="Reset" class="button" />
            </td></tr>
                </table>
            </form>
        </div>

        <p class="center"><a href="login.html"><img src="loginHead.png" alt=""></a></p>
        <p id="bread">Your place:<strong>Update blogs</strong></p>
    </div>
</body>
</html>
