<?php
include('../utils/connection.php');
session_start();
session_regenerate_id(true);

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['action']) || empty($_POST['action'])) {
        if (isset($_POST['highlight-id']) && !empty($_POST['highlight-id'])) {
            $id = $_POST['highlight-id'];

            $sql = "SELECT image_path FROM highlights WHERE id='$id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $target_file = basename($_FILES["image"]["name"]);

            if ($target_file) {
                //CHECK IMAGE UPLOAD
                $imgExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $target_file = uniqid() . time() . "." . pathinfo($target_file, PATHINFO_EXTENSION);

                $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

                if (!in_array($imgExt, $valid_extensions)) {
                    $response['success'] = false;
                    $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    echo json_encode($response);
                    exit();
                }

                move_uploaded_file($_FILES["image"]["tmp_name"], "../admin/assets/uploads/highlight/" . $target_file);
            } else {
                $target_file = $row['image_path'];
            }

            $sql = "UPDATE highlights SET image_path = ?  WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $target_file, $id);
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Item updated successfully.";
                unlink("../admin/assets/uploads/highlight/" . $row['image_path']);
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating item: " . mysqli_error($conn);
            }
        } else if (!isset($_POST['highlight-id']) || empty($_POST['highlight-id'])) {
            // No Theme ID provided, insert new theme
            $target_file = basename($_FILES["image"]["name"]);
            $imgExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $target_file = uniqid() . time() . "." . pathinfo($target_file, PATHINFO_EXTENSION);
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

            $target_file = basename($_FILES["image"]["name"]);
            $imgExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $target_file = uniqid() . time() . "." . pathinfo($target_file, PATHINFO_EXTENSION);

            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');


            if (move_uploaded_file($_FILES["image"]["tmp_name"], "../admin/assets/uploads/highlight/" . $target_file)) {
                $query = "INSERT INTO highlights (image_path) VALUES ('{$target_file}')";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = "Item added successfully.";
                } else {
                    $response['success'] = false;
                    $response['message'] = "Error adding highlight: " . mysqli_error($conn);
                }
            } else {
                $response['success'] = false;
                $response['message'] = "Sorry, there was an error uploading your file." . mysqli_error($conn);
            }
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['highlight-id'])) {
        // Theme removal action
        $highlightId = $_POST['highlight-id'];

        // Sanitize input to prevent SQL injection
        $highlightId = mysqli_real_escape_string($conn, $highlightId);

        //delete pic from the server
        $sql = "SELECT image_path FROM highlights WHERE id='$highlightId'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        unlink("../admin/assets/uploads/highlight/" . $row['image_path']);

        // Delete the theme from the database
        $query = "DELETE FROM highlights WHERE id = $highlightId";
        $result = mysqli_query($conn, $query);


        if ($result) {
            $response['success'] = true;
            $response['message'] = "Highlight removed successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error removing highlight: " . mysqli_error($conn);
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid request.";
    }

    // Fetch updated list of themes
    $query = "SELECT * FROM highlights";
    $result = mysqli_query($conn, $query);
    $highlights = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $response['highlights'] = $highlights;
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Send only JSON data
echo json_encode($response);