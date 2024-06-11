<?php
include('../connection.php');
session_start();

$response = array(); // Array to hold response data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = filter_input(INPUT_POST, "old_pass", FILTER_SANITIZE_SPECIAL_CHARS);
    $new_pass = filter_input(INPUT_POST, "new_pass", FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_pass = filter_input(INPUT_POST, "confirm_pass", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = $_SESSION['username'];

    // Check if any field is empty
    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $response['success'] = false;
        $response['message'] = "All fields are required";
    } else {
        $query  = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($old_pass, $row['password'])) {
                if ($new_pass === $confirm_pass) {
                    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password=? WHERE username=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $hash, $username);
                    mysqli_stmt_execute($stmt);
                    $response['success'] = true;
                    $response['message'] = "Password changed successfully";
                } else {
                    $response['success'] = false;
                    $response['message'] = "New password and confirm password do not match";
                }
            } else {
                $response['success'] = false;
                $response['message'] = "Your old password is incorrect";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "User not found";
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request";
}

echo json_encode($response); // Output response as JSON