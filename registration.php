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
    $username = htmlspecialchars($_POST['Username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $re_password = htmlspecialchars($_POST['re-password']);

    $PasswordHash = password_hash($password, PASSWORD_DEFAULT);

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
    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = "Password must include at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors['password'] = "Password must include at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = "Password must include at least one digit.";
    }

    // Password match check
    if ($password !== $re_password) {
        $errors['re_password'] = "Passwords do not match.";
    }

    // Check if email already exists
    $sql1 = "SELECT * FROM users WHERE email='$email'";
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        $errors['usedEmail'] = "Email already exists";
    }

    // Check if username already exists
    $sql2 = "SELECT * FROM users WHERE username='$username'";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        $errors['username'] = "Username already exists";
    }

    // Proceed with registration if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO users(username, email, password) VALUES(?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $PasswordHash);
            mysqli_stmt_execute($stmt);
             $_SESSION["user"] = "Logged in";
            header("Location: LoggedInPage.php");
            exit;
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
                    <label class="labels">Username
                        <input class="nameinput" type="text" name="Username" value="<?= htmlspecialchars($username); ?>">
                        <?php if (isset($errors['username'])): ?>
                            <div class="error"><?= $errors['username']; ?></div>
                        <?php endif; ?>
                    </label>
                </div>

                <div>
                    <label class="labels">Email

                        <input class="nameinput" required type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="example@example.com">
                        <?php if (isset($errors['email'])): ?>
                            <div class="error"><?= $errors['email']; ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($errors['usedEmail'])): ?>
                            <div class="error"><?= $errors['usedEmail']; ?></div>
                        <?php endif; ?>
                    </label>
                </div>

                <div>
                    <label class="labels">Password

                        <div class="filed">
                            <input id="pass" required type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                            <img onclick="togglePasswordVisibility()" id="passImg" src="img/show.png">
                        </div>

                        <?php if (isset($errors['password'])): ?>
                            <div class="error"><?= $errors['password']; ?></div>
                        <?php endif; ?>
                    </label>

                </div>

                <div>
                    <label class=" labels">Re-enter the Password

                        <div class="filed">
                            <input id="pass" required type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                            <img onclick="togglePasswordVisibility()" id="passImg" src="img/show.png">
                        </div>

                        <?php if (isset($errors['re_password'])): ?>
                            <div class="error"><?= $errors['re_password']; ?></div>
                        <?php endif; ?>
                    </label>

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
