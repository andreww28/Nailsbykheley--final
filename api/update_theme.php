<?php
include('../utils/connection.php');
session_start();
session_regenerate_id(true);

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['theme'])) {
        $theme = $_POST['theme'];

        $query_check = "SELECT theme_id FROM themes WHERE theme = '$theme'";
        $result_check = mysqli_query($conn, $query_check);
        if (mysqli_num_rows($result_check) > 0) {
            $response['success'] = false;
            $response['message'] = "Theme already exists.";
            echo json_encode($response);
            exit(); // Stop further execution
        }

        if (isset($_POST['theme-id']) && !empty($_POST['theme-id'])) {
            // Theme ID provided, update existing theme
            $themeId = $_POST['theme-id'];

            // Sanitize input to prevent SQL injection
            $theme = mysqli_real_escape_string($conn, $theme);
            $themeId = mysqli_real_escape_string($conn, $themeId);

            // Update the theme in the database
            $query = "UPDATE themes SET theme = '$theme' WHERE theme_id = $themeId";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Theme updated successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating theme: " . mysqli_error($conn);
            }
        } else if (!isset($_POST['theme-id']) || empty($_POST['theme-id'])) {
            // No Theme ID provided, insert new theme
            $theme = mysqli_real_escape_string($conn, $theme);

            // Insert the theme into the database
            $query = "INSERT INTO themes (theme) VALUES ('$theme')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $response['success'] = true;
                $response['message'] = "Theme added successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error adding theme: " . mysqli_error($conn);
            }
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['theme-id'])) {
        // Theme removal action
        $themeId = $_POST['theme-id'];

        // Sanitize input to prevent SQL injection
        $themeId = mysqli_real_escape_string($conn, $themeId);

        // Delete the theme from the database
        $query = "DELETE FROM themes WHERE theme_id = $themeId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Theme removed successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error removing theme: " . mysqli_error($conn);
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid request.";
    }

    // Fetch updated list of themes
    $query = "SELECT * FROM themes";
    $result = mysqli_query($conn, $query);
    $themes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $response['themes'] = $themes;
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Send only JSON data
echo json_encode($response);