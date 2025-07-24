<?php
session_start();
include("database.php");

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($username)) {
        $_SESSION["message"] = "Please enter a username.";
    } elseif (empty($password)) {
        $_SESSION["message"] = "Please enter a password.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (user, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $hash);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["message"] = "You are now registered! <br> Thank you for testing this registration form :)";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $_SESSION["message"] = "Username already taken. Please try again.";
            } else {
                $_SESSION["message"] = "Error: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movieboxd</title>
    <link rel="icon" type="image/x-icon" href="movielogo.ico">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">

</head>
    <body>
        <img src="moviesbg.png" class="background-png" alt="Background">

        <div class="form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <img src="movieboxd.png" class="sitelogo" alt="Movieboxd" />
                <h2>Welcome to Movieboxd!</h2>

                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <div class="button-container">
                    <input type="submit" value="Register">
                </div>

                <br>

                <?php if (isset($_SESSION["message"])): ?>
                    <div class="form-message"><?php echo $_SESSION["message"]; ?></div>
                    <?php unset($_SESSION["message"]); ?>
                <?php endif; ?>
            </form>
        </div>
    </body>
</html>
