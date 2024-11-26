<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location:LoggedInPage.php");
    exit();
}


$username = $password = "";
$usernameError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['Username']);
    $password = htmlspecialchars($_POST['password']);

    require_once 'database.php';

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Start session and redirect
            $_SESSION["user"] = "Logged in";
            header("Location: LoggedInPage.php");
            exit();
        } else {
            $passwordError = "Password is incorrect!";
        }
    } else {
        $usernameError = "Username does not match";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="logintAndRegiter.css" type="text/css">
    <!-- <script defer src="login.js"></script> -->
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("pass");
            var img = document.getElementById("passImg");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                img.setAttribute("src", "img/hide.png")
            } else {
                passwordField.type = "password";
                img.setAttribute("src", "img/show.png")

            }
        }
    </script>
</head>

<body>
    <div class="container">

        <div class="left_container">
            <h1 class="title">Begin <p>The Action</p>
            </h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login">
                <div>
                    <label class="labels">Username 
                        <input class="nameinput" required type="text" value="<?php echo htmlspecialchars($username); ?>" name="Username" required>
                        <div class="error"> <?php echo $usernameError; ?></div>
                    </label>

                </div>

                <div>
                    <label class="labels">Password
                        <div class="filed">
                            <input id="pass" required type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                            <img onclick="togglePasswordVisibility()" id="passImg" src="img/show.png">
                        </div>
                        <div class="error"> <?php echo $passwordError; ?></div>
                    </label>
                        
                </div>

                <input class="loginButton" type="submit" value="Login">
                <div class="links">
                    <a href="registration.php">Sign up</a>
                    <a href="index.php">Back</a>
                </div>
            </form>

        </div>

        <div class="right_container">
            <img src="logo.png" class="logo" alt="">
            <img class="cover" src="img/wallpaper.jpg">
        </div>

    </div>
</body>

</html>