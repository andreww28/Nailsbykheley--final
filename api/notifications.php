<?php
include('../utils/connection.php');

function notifications_add_data($title, $referenceNumber, $conn)
{
    if ($title === "New Appointment") {
        $message = "You have new pending appointment (" . $referenceNumber . ").";
    } else {
        $message = $referenceNumber . " cancelled his/her appointment.";
    }

    $query = "INSERT INTO notifications (title, message) VALUES (?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $title, $message);
    mysqli_stmt_execute($stmt);
    exit();
}



function getData($query, $conn)
{
    $data = array();
    // Prepare statement
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch data as associative array
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        // Free result set
        mysqli_free_result($result);
    }

    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'add_data' && isset($_POST['title']) && isset($_POST['msg'])) {
        $title = $_POST['title'];
        $msg = $_POST['msg'];

        $sql = "INSERT INTO notifications (title, message) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $title, $msg);



        if (mysqli_stmt_execute($stmt)) {
            // Success
            echo "Notification added successfully.";
        } else {
            // Error
            echo "Error adding notification: " . mysqli_stmt_error($stmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
    if ($_POST['action'] === 'get_data') {
        $sql1 = "SELECT * FROM notifications ORDER BY timestamp DESC";

        $notifs = getData($sql1, $conn);
        // Close connection
        // Convert the result to JSON and send it back to JavaScript

        $output = array(
            'data' => $notifs,
        );

        echo json_encode($output);
        exit();
    } else if ($_POST['action'] === 'remove_data') {

        if (!isset($_POST['all'])) {
            $id = $_POST["id"];
            $sql = "DELETE FROM notifications WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $id);
        } else if (isset($_POST['all'])) {
            $sql = "DELETE FROM notifications";
            $stmt = mysqli_prepare($conn, $sql);
        }
        // Bind the parameter

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Success
            echo "Notification removed successfully.";
        } else {
            // Error
            echo "Error removing notification: " . mysqli_stmt_error($stmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else if ($_POST['action'] === 'update_status') {
        if (!isset($_POST['all'])) {
            $id = $_POST["id"];
            $sql = "UPDATE notifications SET read_status = 'read' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $id);
        } else if (isset($_POST['all'])) {
            $sql = "UPDATE notifications SET read_status = 'read'";
            $stmt = mysqli_prepare($conn, $sql);
        }
        // Bind the parameter

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Success
            echo "Notification updated successfully.";
        } else {
            // Error
            echo "Error updating notification: " . mysqli_stmt_error($stmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else if ($_POST['action'] === 'get_notif_count') {
        $query = "SELECT COUNT(*) as count FROM notifications WHERE read_status = 'unread'";
        $notifs = getData($query, $conn);
        // Close connection
        // Convert the result to JSON and send it back to JavaScript

        $output = array(
            'count' => $notifs,
        );

        echo json_encode($output);
        exit();
    }
}
