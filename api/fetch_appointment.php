<?php
// Include your database connection file
include('../utils/connection.php');

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

if (isset($_POST['selected_date'])) {
    $date = $_POST['selected_date'];

    // Sanitize the input (optional but recommended)
    $date = filter_var($date, FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT a.*,CONCAT(DATE_FORMAT(a.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(a.end_time, '%h:%i %p')) AS time, ui.*, uc.* 
    FROM appointments AS a
    LEFT JOIN user_info AS ui ON a.userId = ui.userId
    LEFT JOIN user_conditions AS uc ON a.conditionId = uc.conditionId WHERE appointment_date = '$date' AND (status = 'pending' OR status = 'confirmed') ORDER BY a.start_time";

    $output = getData($query, $conn);

    mysqli_close($conn);
    echo json_encode($output);
    exit();
}

if (isset($_POST['month'])) {
    // Get the month value from the AJAX request
    $month = $_POST['month'];

    // Sanitize the input (optional but recommended)
    $month = filter_var($month, FILTER_SANITIZE_NUMBER_INT);

    // Validate the month value
    if (is_numeric($month) && $month >= 0 && $month <= 11) {
        // Prepare SQL query
        $sql1 = "SELECT referenceNum, appointment_date, start_time, end_time, status FROM appointments WHERE MONTH(appointment_date) = '$month' AND (status = 'pending' OR status = 'confirmed')";
        $sql2 = "SELECT * FROM available_time";

        // // Prepare statement
        // $stmt = mysqli_prepare($conn, $sql);

        // // Bind parameters
        // mysqli_stmt_bind_param($stmt, "i", $month);

        // // Execute statement
        // mysqli_stmt_execute($stmt);

        // // Get result
        // $result = mysqli_stmt_get_result($stmt);

        // // Fetch data as associative array
        // $data = array();
        // while ($row = mysqli_fetch_assoc($result)) {
        //     $data[] = $row;
        // }

        // // Close statement
        // mysqli_stmt_close($stmt);

        $data = getData($sql1, $conn);
        $schedule = getData($sql2, $conn);

        // Close connection
        mysqli_close($conn);

        // Convert the result to JSON and send it back to JavaScript

        $output = array(
            'data' => $data,
            'available_time' => $schedule
        );

        echo json_encode($output);
        exit();
    }
}






// Define your SQL query
$status = $_POST['c_t'];
$sql = "SELECT 
            appointments.referenceNum,
            user_info.fullName,
            user_info.mnumber,
            appointments.appointment_date,
            CONCAT(DATE_FORMAT(appointments.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(appointments.end_time, '%h:%i %p')) AS time,
            appointments.service
        FROM 
            appointments
        INNER JOIN 
            user_info ON appointments.userId = user_info.userId
        WHERE
            appointments.status = '$status'
        ";

// Execute the query
$result = mysqli_query($conn, $sql);
$total_all_rows = mysqli_num_rows($result);

$columns = array(
    0 => 'appointments.referenceNum',
    1 => 'user_info.fullName',
    2 => 'user_info.mnumber',
    3 => 'appointments.appointment_date', // Use 'theme' instead of 'theme_id' for display
    4 => 'time',
    5 => 'appointments.service',
);

if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search_value = $_POST['search']['value'];
    $sql .= " AND (appointments.referenceNum LIKE '%" . $search_value . "%'";
    $sql .= " OR user_info.fullName LIKE '%" . $search_value . "%')";
}

if (!empty($_POST['registered_from']) && !empty($_POST['registered_to'])) {
    $sql .= "AND appointment_date BETWEEN '{$_POST['registered_from']}' AND '{$_POST['registered_to']}' ";
}


if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];

    $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
} else {
    $sql .= " ORDER BY appointments.referenceNum DESC"; // Default sorting if not provided
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}

$query = mysqli_query($conn, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();

// Fetch data and format it
while ($row = mysqli_fetch_assoc($query)) {
    // Create button IDs based on reference number
    // Add data to sub-array
    $sub_array = array(
        $row["referenceNum"],
        $row["fullName"],
        $row["mnumber"],
        str_replace('-', '/', $row["appointment_date"]),
        str_replace(' ', '', $row["time"]),
        $row["service"]
    );

    // Push sub-array to data array
    $data[] = $sub_array;
}


$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $total_all_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);

echo json_encode($output);

// Close connection
// mysqli_close($conn);