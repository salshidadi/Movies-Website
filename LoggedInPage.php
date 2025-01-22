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
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="index.js"></script>
</head>

<body>

    <div class="trailler-c hide">
        <div class="trailler">
            <iframe class="trailler-video" width="100%" height="100%" src=""></iframe>
        </div>

    </div>

    <nav>

        <a href= "LoggedInPage.php"><img src="logo.png" class="logo" alt=""></a>

        <div class="logedin">

            <a href="LoggedInPage.php" class="btn btn-1"> Home</a>
            <a href="watchList.php" class="btn btn-1"> My watch list</a>
            <a href="favoriteMovie.php" class="btn btn-1"> My favorite</a>
            <a href="logout.php" class="btn btn-1"> Logout</a>

            <img class="profile-photo" src="img/userIcons/icon-1.png" width="50px" height="50px">
        </div>

    </nav>


    <div class="header">


        <div class="info">
            <h1 class="movie-title"></h1>
            <p class="movie-dsc"></p>
            <div class="detiles">
                <button class="btnMain watch-trailer"><img src="img/play.png" width="16px" height="16px">Play</button>
            </div>
        </div>

        <div class="fade"></div>
        <img class="cover" src="" width="100%">
    </div>

    <div class="search-container">
            <div class="topAndTrend">
                <button class="tendBtn flterStyle activated" id="trendBtn"><img class="fire" src="img/fire.gif" width="40px" height="40px"><p>Trending</p></button>
                <button class="rateBtn flterStyle" id="rateBtn"><img src="img/rate.gif" width="40px" height="40px"><p>Top</p></button>
            </div>
    
            <form>
                <input class="search-bar" type="text" placeholder="search">
                <label><input class="checkbox" type="checkbox">search by actor</label>
            </form>
            
            <div class="container">
                <div class="select-btn">
                    <span class="btn-text">Select The Genre</span>
                    <span class="arrow-dwn">
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                </div>
                <ul class="list-items">

                </ul>
            </div>
        </div>

    <div class="movie-cards">

    </div>

    <footer class="footer">
        <img src="logo.png" class="logo" alt="">
        <p>all copyrights saved for Zoneless</p>
    </footer>

</body>

</html>