
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
            <a herf="index.php"><img src="logo.png" class="logo" alt=""></a>

            <div class="notLogin">
                <a href="login.php" class="btn btn-2"> Login</a>
                <a href="signup.php" class="btn btn-3"> Register Now</a>
            </div>
            
        </nav>


        <div class="container">
            
        </div>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="closeC">
                <span class="close">&times;</span>
                </div>
                <div class="rateCon">
                    <p class="rateThis">Rate this</p>
                    <div class="rateStrars">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                    </div>
                </div>
            </div>

        </div>

        <footer class="footer">
            <img src="logo.png" class="logo" alt="">
            <p>all copyrights saved for Zoneless</p>
        </footer>
    
    </body>
</html>
