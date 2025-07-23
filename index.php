<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>WELCOME TO FAKEBOOK!</h2>
        username:<br>
        <input type="text" name="username"><br>
        password:<br>
        <input type="password" name="password"><br>
        <br>
        <input type="submit" name="submit" value="register">
        <br><br>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        echo "Please enter a username<br><br>";
    } elseif (empty($password)) {
        echo "Please enter a password<br><br>";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (user, password)
                VALUES ('$username', '$hash')";

        if (mysqli_query($conn, $sql)) {
            echo "You are now registered!";
        } else {
            if (mysqli_errno($conn) == 1062) {
                echo "Username/password already taken. Please try again.<br><br>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

mysqli_close($conn);
?>