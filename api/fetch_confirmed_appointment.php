<?php
// Include your database connection file
include('../utils/connection.php');

// Define your SQL query
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
            appointments.status = 'confirmed'
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