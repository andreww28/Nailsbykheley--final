<?php

function retrieve_data($query)
{
    include('../utils/connection.php');
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle the error if the query fails
        $error = mysqli_error($conn);
        // You might want to log the error or return an error response
        echo json_encode(array('error' => $error));
        // Exit the script to prevent further execution
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_GET['month'])) {
    $month = $_GET['month'];
    $off_days_query = "SELECT * FROM off_days WHERE MONTH(from_date) = '$month' OR MONTH(to_date) = '$month' ORDER BY from_date";
} else {
    $off_days_query = "SELECT * FROM off_days ORDER BY from_date";
}

// Fetch all rows as associative arrays
$available_time = retrieve_data("SELECT * FROM available_time ORDER BY from_time");
$off_days = retrieve_data($off_days_query);

// Combine both datasets into a single associative array
$data = array(
    'available_time' => $available_time,
    'off_days' => $off_days
);

// Set the response header to JSON
header('Content-Type: application/json');

// Return the combined data as JSON
echo json_encode($data);