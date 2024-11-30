<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moana 2</title>
    <link rel="stylesheet" href="moviePageStyle.css">
    <script defer src="moviePage.js"></script>
</head>
<body>

    <nav>
        
        <a href='index.php'><img src="logo.png" class="logo" alt=""></a>

        <div class="notLogin">
            <a href="login.php" class="btn btn-2"> Login</a>
            <a href="signup.php" class="btn btn-3"> Register Now</a>
        </div>
        
    </nav>
    
    </body>
</html>