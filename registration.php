<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: LoggedInPage.php");
    exit;
}

require_once 'database.php';

$username = $email = $password = $re_password = "";
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['Username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $re_password = htmlspecialchars($_POST['re-password']);
    /*htmlspecialchars():
        A PHP function that converts special characters in a string to their HTML entities.
        For example: < becomes &lt;
                     > becomes &gt;
                    " becomes &quot;
                    ' becomes &#039;
    This prevents malicious HTML or JavaScript from being executed if the input is later displayed on a web page (Cross-Site Scripting or XSS attacks). */

    // Username validations
    if (empty($username)) {
        $errors['username'] = "Username cannot be empty";
    } elseif (strlen($username) < 4) {
        $errors['username'] = "Username must be at least 4 characters.";
    } elseif (strpos($username, ' ') !== false) {
        $errors['username'] = "Username cannot contain any whitespace";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is not valid!";
    }

    // Password strength validations
    // Length of the password at least 8 characters
    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }
    // The password must include at least on uppercase letter
    elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = "Password must include at least one uppercase letter.";
    }
    //Password must include at least one lowercase letter
    elseif (!preg_match('/[a-z]/', $password)) {
        $errors['password'] = "Password must include at least one lowercase letter.";
    }
    //Password must include at least one digit
    elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = "Password must include at least one digit.";
    }

    // Password match check
    if ($password !== $re_password) {
        $errors['re_password'] = "Passwords do not match.";
    }

    // SQL query to check if a user exists with the provided email or username
    $sqlCheck = "SELECT * FROM user WHERE Email = ? OR Username = ?";

    // Prepare the SQL statement using the database connection
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);

    // Bind the input variables ($email and $username) to the prepared statement
    mysqli_stmt_bind_param($stmtCheck, "ss", $email, $username);

    //// Execute the prepared statement
    mysqli_stmt_execute($stmtCheck);

    // Get the result of the executed statement
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    // Check if the query returned any rows
    if (mysqli_num_rows($resultCheck) > 0) {
        // Loop through the rows to check email and username
        while ($row = mysqli_fetch_assoc($resultCheck)) {
            if ($row['Email'] === $email) {
                $errors['usedEmail'] = "Email already exists";
            }
            if ($row['Username'] === $username) {
                $errors['username'] = "Username already exists";
            }
        }
    }

    // Proceed with registration if no errors
    if (empty($errors)) {

        $PasswordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert a new WatchList and get WatchListID
        $sqlWatchList = "INSERT INTO WatchList () VALUES ()";
        if (mysqli_query($conn, $sqlWatchList)) {
            $watchListID = mysqli_insert_id($conn); // Get the last inserted ID
        } else {
            die("Error creating watchlist: " . mysqli_error($conn));
        }

        // Insert a new FavoriteMoviesList and get FavoriteListID
        $sqlFavoriteList = "INSERT INTO FavoriteMoviesList () VALUES ()";
        if (mysqli_query($conn, $sqlFavoriteList)) {
            $favoriteListID = mysqli_insert_id($conn); // Get the last inserted ID
        } else {
            die("Error creating favorite list: " . mysqli_error($conn));
        }

        // Define the SQL query to insert a new user into the users table
        $sqlUser = "INSERT INTO user (Username, Email, Password, WatchList_WatchListID, FavoriteMovies_FavoriteListID)
                    VALUES (?, ?, ?, ?, ?)"; // The ? placeholders allow for dynamic input while preventing SQL injection.


        // Initialize a statement object to be used for the SQL query
        $stmt = mysqli_stmt_init($conn);

        // Prepare the SQL query using the statement object
        if (mysqli_stmt_prepare($stmt, $sqlUser)) {
            // Bind the actual values to the placeholders in the SQL query
            mysqli_stmt_bind_param($stmt, "sssii", $username, $email, $PasswordHash, $watchListID, $favoriteListID);
            if (mysqli_stmt_execute($stmt)) { // Execute the prepared SQL statement
                $_SESSION["user"] = $username;
                header("Location: LoggedInPage.php");
                exit;
            } else {
                die("Error inserting user: " . mysqli_error($conn));
            }
        } else {
            die("Error preparing statement: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="logintAndRegiter.css" type="text/css">
    <script defer src="login.js"></script>
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

        function toggleRePasswordVisibility() {
            var RepasswordField = document.getElementById("repass");
            var img = document.getElementById("rePassImg");
            if (RepasswordField.type === "password") {
                RepasswordField.type = "text";
                img.setAttribute("src", "img/hide.png")
            } else {
                RepasswordField.type = "password";
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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="register">
                <div>
                    <label class="labels">Username</label>
                    <?php if (isset($errors['username'])): ?>
                        <div class="error"><?= $errors['username']; ?></div>
                    <?php endif; ?>
                    <input type="text" name="Username" value="<?= htmlspecialchars($username); ?>">
                </div>

                <div>
                    <label class="labels">Email</label>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= $errors['email']; ?></div>
                    <?php endif; ?>
                    <?php if (isset($errors['usedEmail'])): ?>
                        <div class="error"><?= $errors['usedEmail']; ?></div>
                    <?php endif; ?>
                    <input required type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="example@example.com">
                </div>

                <div>
                    <label class="labels">Password</label>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error"><?= $errors['password']; ?></div>
                    <?php endif; ?>
                    <input style="display: inline;" type="password" id="pass" name="password" value="<?= htmlspecialchars($password); ?>" required>
                    <img onclick="togglePasswordVisibility()" id="passImg" height="25px" width="25px" src="img/show.png">
                </div>

                <div>
                    <label class=" labels">Re-enter the Password</label>
                    <?php if (isset($errors['re_password'])): ?>
                        <div class="error"><?= $errors['re_password']; ?></div>
                    <?php endif; ?>
                    <input style="display: inline;" type="password" id="repass" name="re-password" value="<?= htmlspecialchars($re_password); ?>" required>
                    <img onclick="toggleRePasswordVisibility()" id="rePassImg" height="25px" width="25px" src="img/show.png">


                </div>





                <input class="loginButton" type="submit" value="Sign up">
                <div class="links">
                    <a href="login.php">Login</a>
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
