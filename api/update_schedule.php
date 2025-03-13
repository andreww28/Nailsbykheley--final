<?php
include('../utils/connection.php');
session_start();
session_regenerate_id(true);

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['from-time'])) {
        $from_time = mysqli_real_escape_string($conn, $_POST["from-time"]);
        $to_time = mysqli_real_escape_string($conn, $_POST["to-time"]);

        // Insert the theme into the database
        $query = "INSERT INTO available_time (from_time, to_time) VALUES ('$from_time', '$to_time')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Time added successfully.";
            $response['_type'] = "time";
        } else {
            $response['success'] = false;
            $response['message'] = "Error adding Time: " . mysqli_error($conn);
        }
    } else if (isset($_POST['from-date'])) {
        $from_date = mysqli_real_escape_string($conn, $_POST["from-date"]);
        $to_date = mysqli_real_escape_string($conn, $_POST["to-date"]);

        // Insert the theme into the database
        $query = "INSERT INTO off_days (from_date, to_date) VALUES ('$from_date', '$to_date')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Date added successfully.";
            $response['_type'] = "date";
        } else {
            $response['success'] = false;
            $response['message'] = "Error Date theme: " . mysqli_error($conn);
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['time-id'])) {
        // Theme removal action
        $timeId = $_POST['time-id'];

        // Sanitize input to prevent SQL injection
        $timeId = mysqli_real_escape_string($conn, $timeId);

        // Delete the theme from the database
        $query = "DELETE FROM available_time WHERE id = $timeId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Time removed successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error removing time: " . mysqli_error($conn);
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['date-id'])) {
        // Theme removal action
        $dateId = $_POST['date-id'];

        // Sanitize input to prevent SQL injection
        $dateId = mysqli_real_escape_string($conn, $dateId);

        // Delete the theme from the database
        $query = "DELETE FROM off_days WHERE id = $dateId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Date removed successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error removing time: " . mysqli_error($conn);
        }
    }

    function retrieveData($conn, $query)
    {
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $response['available_time'] = retrieveData($conn, "SELECT * FROM available_time ORDER BY from_time");
    $response['off_days'] = retrieveData($conn, "SELECT * FROM off_days ORDER BY from_date");
}

echo json_encode($response);