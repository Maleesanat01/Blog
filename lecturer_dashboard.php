<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Lecturer Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Lecturer Dashboard</h1>
 
            <a href="index.php" class="logout-btn">Logout</a>
       
        <div class="options">
            <a href="upload-blog-form.php" class="btn option-btn">Upload Blog</a>
            <!--a href="display_blog.php" class="btn option-btn">See My Blogs</a-->
        </div>
        <?php
        include "display_blog.php";
        ?>
    </div>
</body>
</html>
