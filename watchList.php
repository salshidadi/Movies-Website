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
    <title>Document</title>
    <link rel="stylesheet" href="list2.css" type="text/css">
</head>

<body>
    <div class="container">

        <nav>

            <img src="logo.png" class="logo" alt="">

            <div class="logedin">

                <a href="index.php" class="btn btn-1"> Home</a>
                <a href="watchList.php" class="btn btn-1"> My watch list</a>
                <a href="#" class="btn btn-1"> My favorite</a>

                <img class="profile-photo" src="img/userIcons/icon-1.png" width="50px" height="50px">
            </div>

        </nav>

        <div class="innerContainer">
            <div class="empty hide">
                <img src="img/ghost.png" width="200px">
                <p>add some movie to watch later</p>
            </div>

            <div class="movie-cards">

                <div class="one-card">
                    <div class="poster">
                        <img src="img/poster.jpg">
                    </div>

                    <div class="data">
                        <div class="partone">
                            <p class="title">The Wild Robot</p>
                            <span>
                                <p>2025</p>
                                <p>1:30</p>
                                <p>Movie</p>
                            </span>

                            <span>
                                <p>Rate 9.2 (306k)</p>
                                <p>Rate</p>
                            </span>
                        </div>

                        <div class="partTow">
                            <p class="type">Action</p>
                            <p class="type">Adventure</p>
                            <p class="type">Animation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>

</html>