<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: LoggedInPage.php");
    exit();
}

$username = $password = "";
$usernameError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['Username']);
    $password = htmlspecialchars($_POST['password']);

    require_once 'database.php';

    // Check if the database connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM user WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("SQL prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    if (!mysqli_stmt_execute($stmt)) {
        die("SQL execution failed: " . mysqli_error($conn));
    }

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // Check if user exists
    if ($user) {
        
        if (password_verify($password, $user["Password"])) {
            // Start session and redirect to the logged-in page
            $_SESSION["user"] = $username;
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
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("pass");
            var img = document.getElementById("passImg");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                img.setAttribute("src", "img/hide.png");
            } else {
                passwordField.type = "password";
                img.setAttribute("src", "img/show.png");
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
                    <label class="labels">Username </label>
                    <div class="error"> <?php echo $usernameError; ?></div>
                    <input required class="labels" type="text" value="<?php echo htmlspecialchars($username); ?>" name="Username" required>
                </div>

                <div>
                    <label class="labels">Password </label>
                    <div class="error"> <?php echo $passwordError; ?></div>
                    <input id="pass" style="display: inline;" class="labels" required type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                    <img onclick="togglePasswordVisibility()" id="passImg" height="25px" width="25px" src="img/show.png">
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
