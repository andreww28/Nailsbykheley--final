<?php
include('../utils/connection.php');

$response = array();


function get_string_ids($data_name, $conn)
{
    $idsArray = json_decode($_POST[$data_name], true);

    // Initialize an empty array to store escaped IDs
    $escapedIds = array();

    // Escape each ID in the array
    foreach ($idsArray as $id) {
        $escapedIds[] = "'" . mysqli_real_escape_string($conn, $id) . "'";
    }

    // Convert the array of escaped IDs into a comma-separated string
    return implode(',', $escapedIds);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['title'])) {

        if (!isset($_POST['gallery_id']) || empty($_POST['gallery_id'])) {
            $theme_id = $_POST["themes"];
            $title = $_POST["title"];

            // File upload handling
            $target_file = basename($_FILES["image"]["name"]);
            $imgExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $target_file = uniqid() . time() . "." . pathinfo($target_file, PATHINFO_EXTENSION);

            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

            //CHECK IF THE ENTRY EXIST;
            $query_check = "SELECT title FROM gallery WHERE title = '$title'";
            $result_check = mysqli_query($conn, $query_check);
            if (mysqli_num_rows($result_check) > 0) {
                $response['success'] = false;
                $response['message'] = $title . " already exists.";
                echo json_encode($response);
                exit(); // Stop further execution
            }

            if (!in_array($imgExt, $valid_extensions)) {
                $response['success'] = false;
                $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                echo json_encode($response);
                exit();
            }

            // Insert the theme into the database
            if (move_uploaded_file($_FILES["image"]["tmp_name"], "../admin/assets/uploads/gallery/" . $target_file)) {
                $_errorMsg =  "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

                // Insert data into gallery table
                $sql_insert = "INSERT INTO gallery (title, theme_id, image_path, _type) VALUES (?, ?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "siss", $title, $theme_id, $target_file, $_POST['img_type']);

                if (mysqli_stmt_execute($stmt_insert)) {
                    $response['success'] = true;
                    $response['message'] = "Item added successfully.";
                } else {
                    $response['success'] = false;
                    $response['message'] = "Error adding item: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt_insert);
            } else {
                $response['success'] = false;
                $response['message'] = "Sorry, there was an error uploading your file." . mysqli_error($conn);
            }

            // update data
        } else if (isset($_POST['gallery_id']) || !empty($_POST['gallery_id'])) {
            $title = $_POST["title"];
            $id = $_POST['gallery_id'];
            $theme_id = $_POST['themes'];
            $_type = $_POST['img_type'];

            //CHECK IF THE ENTRY EXIST;
            $query_check = "SELECT title FROM gallery WHERE title = '$title' AND NOT id='$id'";
            $result_check = mysqli_query($conn, $query_check);
            if (mysqli_num_rows($result_check) > 0) {
                $response['success'] = false;
                $response['message'] = $title . " already exists.";
                echo json_encode($response);
                exit(); // Stop further execution
            }


            $sql = "SELECT image_path FROM gallery WHERE id='$id'";
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

                unlink("../admin/assets/uploads/gallery/" . $row['image_path']);
                move_uploaded_file($_FILES["image"]["tmp_name"], "../admin/assets/uploads/gallery/" . $target_file);
            } else {
                $target_file = $row['image_path'];
            }

            // Perform the update in the database (replace with your actual update query)
            $sql = "UPDATE gallery SET title = ?, theme_id = ?, image_path = ?, _type = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sissi", $title, $theme_id, $target_file, $_type, $id);
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Item updated successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating item: " . mysqli_error($conn);
            }
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['ids'])) {
        $idsString = get_string_ids('ids', $conn);

        // Sanitize input to prevent SQL injection
        // $id = mysqli_real_escape_string($conn, $id);

        //retrieve certain image by id
        $sql = "SELECT image_path FROM gallery WHERE id IN ($idsString)";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                unlink("../admin/assets/uploads/gallery/" . $row['image_path']);
            }
        }

        // Delete the theme from the database
        $query = "DELETE FROM gallery WHERE id IN ($idsString)";
        $result = mysqli_query($conn, $query);


        if ($result) {
            $response['success'] = true;
            $response['message'] = "Item/s removed successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error removing item: " . mysqli_error($conn);
        }
    }
}
echo json_encode($response);
