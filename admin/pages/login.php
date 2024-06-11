<?php

require('../../utils/connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/login.css" />
    <title>Admin Login</title>
</head>

<body>
    <div class="main-container">
        <div class="img-container">
            <img src="../assets/img/logo.jpg" alt="Business logo" />
        </div>

        <div class="content-container">
            <h1>Log In</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

                <label for="username">Username:</label>
                <input type="text" name="username" id="username">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password">

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
                    $errorMsg = "";
                    if (!empty($username) || !empty($password)) {
                        $query  = "SELECT * FROM users WHERE username = '$username'";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) == 1) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                if (password_verify($password, $row['password'])) {
                                    session_start();
                                    $_SESSION['username'] = $row['username'];
                                    header("Location:dashboard.php");
                                } else {
                                    $errorMsg = "Username or Password is invalid!";
                                }
                            }
                        } else {
                            $errorMsg = "Username does not exist!";
                        }
                    } else {
                        $errorMsg = "Username and Password is required!";
                    }

                    echo "<p class='error-msg'>$errorMsg</p>";
                }

                ?>
                <div class="btn-div">
                    <input type="submit" name="submit" value="Login" id="submit-btn" />
                </div>
            </form>

        </div>
    </div>
</body>

</html>