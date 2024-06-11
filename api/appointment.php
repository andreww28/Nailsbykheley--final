<?php

include('../utils/connection.php');

session_start();
session_regenerate_id(true);

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

function generateSequentialCounter()
{
    // Retrieve the current counter value from a file
    $counterFile = "counter.txt";
    $counter = file_get_contents($counterFile);

    // Increment the counter
    $counter++;

    // Save the updated counter back to the file
    file_put_contents($counterFile, $counter);

    // Return the formatted counter value
    return str_pad($counter, 6, "0", STR_PAD_LEFT); // Assuming a 6-digit counter with leading zeros
}

function generateReferenceNumber()
{
    $prefix = "APPT";
    $date = date("Ymd");
    $counter = generateSequentialCounter();
    return $prefix . "-" . $date . "-" . $counter;
}

function generateSecureToken($length = 32)
{
    return bin2hex(random_bytes($length));
}



function remove_multiple_data($action, $conn)
{
    $cond = null;
    switch ($action) {
        case 'delete_pending':
            $cond = 'pending';
            break;
        case 'delete_completed':
            $cond = 'completed';
            break;
        case 'delete_cancelled':
            $cond = 'cancelled';
            break;
    }

    $idsString = get_string_ids('ids', $conn);

    // Define the SQL query to delete rows with IDs in the array
    $sql = "DELETE appointments, user_conditions, user_info
        FROM appointments
        LEFT JOIN user_conditions ON appointments.conditionId = user_conditions.conditionId
        LEFT JOIN user_info ON appointments.userId = user_info.userId
        WHERE appointments.status = '$cond' AND appointments.referenceNum IN ($idsString)";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // If deletion is successful
        $response['success'] = true;
        $response['message'] = "Appointment successfully deleted.";
    } else {
        // If an error occurred
        $response['success'] = false;
        $response['message'] = "Appointment unsuccessfully deleted.";
    }

    echo json_encode($response);
}


function update_status($action, $conn)
{
    $new_status = '';
    $old_status = '';
    $success_msg = '';
    switch ($action) {
        case 'confirm_pending':
            $new_status = 'confirmed';
            $old_status = 'pending';
            $success_msg = 'Appointment approved successfully.';
            break;
        case 'delete_confirmed':
            $new_status = 'cancelled';
            $old_status = 'confirmed';
            $success_msg = 'Appointment successfully cancelled.';
            break;
        case 'complete_confirmed':
            $new_status = 'completed';
            $old_status = 'confirmed';
            $success_msg = 'Appointment done successfully.';
            break;
    }

    $idsString = get_string_ids('ids', $conn);

    $sql = "UPDATE appointments SET status = '$new_status' WHERE status = '$old_status' AND referenceNum IN ($idsString)";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // If deletion is successful
        $response['success'] = true;
        $response['message'] = $success_msg;
    } else {
        // If an error occurred
        $response['success'] = false;
        $response['message'] = "Failed to approve appointment.";
    }

    echo json_encode($response);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (($_POST['action'] === 'delete_pending' || $_POST['action'] === 'delete_completed' || $_POST['action'] === 'delete_cancelled') && isset($_POST['ids'])) {
        remove_multiple_data($_POST['action'], $conn);
    } else if (($_POST['action'] === 'confirm_pending' || $_POST['action'] === 'delete_confirmed' || $_POST['action'] === 'complete_confirmed') && isset($_POST['ids'])) {
        update_status($_POST['action'], $conn);
    } else if (isset($_POST['action']) && $_POST['action'] === 'view_full_info' && isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        // Define the SQL query to retrieve full information based on the ID
        $sql = "SELECT a.*, ui.*, uc.* 
                FROM appointments AS a
                LEFT JOIN user_info AS ui ON a.userId = ui.userId
                LEFT JOIN user_conditions AS uc ON a.conditionId = uc.conditionId
                WHERE a.referenceNum = ?";

        // Prepare the statement
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            // If data is found, return it in the response
            $response['success'] = true;
            $response['data'] = $row;
        } else {
            // If no data is found or an error occurred
            $response['success'] = false;
            $response['message'] = "No information found for the given ID: " . $id;
        }

        mysqli_stmt_close($stmt);
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] === 'get_appt_count') {
        $condition = "";
        switch ($_POST['date_range']) {
            case 'this_week':
                $condition = 'WHERE WEEK(appointment_date) = WEEK(CURDATE())';
                break;
            case 'today':
                $condition = 'WHERE DATE(appointment_date) = CURDATE()';
                break;
            case 'this_month':
                $condition = 'WHERE MONTH(appointment_date) = MONTH(CURDATE())';
                break;
            case 'this_year':
                $condition = 'WHERE YEAR(appointment_date) = YEAR(CURDATE())';
                break;
        }

        $query = "SELECT status, COUNT(*) as count FROM appointments "  . $condition . " GROUP BY status";

        $result = mysqli_query($conn, $query);
        if ($result) {
            // If deletion is successful
            $response['success'] = true;
            $response['message'] = "success";

            while ($row = mysqli_fetch_assoc($result)) {
                $response[$row['status']] = $row['count'];
            }
        } else {
            // If an error occurred
            $response['success'] = false;
            $response['message'] = "Failed to approve appointment.";
        }

        echo json_encode($response);
    } else if (isset($_POST["name"]) && $_POST['action'] === 'add_data') {
        // 

        $referenceNumber = generateReferenceNumber();
        $verification_code = generateSecureToken(16);


        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $mnumber = filter_input(INPUT_POST, 'mnumber', FILTER_SANITIZE_SPECIAL_CHARS);
        $isFirstTime = filter_input(INPUT_POST, 'isFirsttime', FILTER_SANITIZE_SPECIAL_CHARS);
        $allergicText = filter_input(INPUT_POST, 'allergicText', FILTER_SANITIZE_SPECIAL_CHARS);

        $sport = filter_input(INPUT_POST, 'sport', FILTER_SANITIZE_SPECIAL_CHARS);
        $service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_SPECIAL_CHARS);
        $appt_date = filter_input(INPUT_POST, 'appointment-date', FILTER_SANITIZE_SPECIAL_CHARS);
        $start_time = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_SPECIAL_CHARS);
        $end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_SPECIAL_CHARS);
        $allergicSpecify = filter_input(INPUT_POST, 'allergicSpecify', FILTER_SANITIZE_SPECIAL_CHARS);
        $sportSpecify = filter_input(INPUT_POST, 'sportSpecify', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = "pending";

        $medical_conditions_array = isset($_POST['condition']) ? $_POST['condition'] : [];
        $nail_conditions_array = isset($_POST['nail-condition']) ? $_POST['nail-condition'] : [];

        // Sanitize and validate each condition
        $medical_conditions_sanitized = [];
        foreach ($medical_conditions_array as $condition) {
            // Sanitize and validate each condition
            $condition = filter_var($condition, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // You can add additional validation if needed
            if ($condition !== false && $condition !== '') {
                $medical_conditions_sanitized[] = $condition;
            }
        }

        $nail_conditions_sanitized = [];
        foreach ($nail_conditions_array as $condition) {
            // Sanitize and validate each condition
            $condition = filter_var($condition, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // You can add additional validation if needed
            if ($condition !== false && $condition !== '') {
                $nail_conditions_sanitized[] = $condition;
            }
        }

        // Convert sanitized arrays to comma-separated strings
        $medical_condition = implode(', ', $medical_conditions_sanitized);
        $nail_condition = implode(', ', $nail_conditions_sanitized);


        $statuses = array("pending", "confirmed");

        // Prepare the SELECT query
        $query = "SELECT COUNT(*) AS count FROM user_info 
          INNER JOIN appointments ON user_info.userId = appointments.userId 
          WHERE user_info.fullName = ? AND appointments.status IN (?, ?)";

        // Prepare the statement
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "sss", $name, $statuses[0], $statuses[1]);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Bind result variables
        mysqli_stmt_bind_result($stmt, $count);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        // Check if the entry exists
        if ($count > 0) {
            $response['success'] = false;
            $response['message'] = "This appointment already exist!.";
            echo json_encode($response);
            exit();
        } else {
            // Insert the theme into the database
            $query1 = "INSERT INTO user_info (fullName, address, email, mnumber) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($conn, $query1);
            mysqli_stmt_bind_param($stmt1, "ssss", $name, $address, $email, $mnumber);
            mysqli_stmt_execute($stmt1);

            // Retrieve the auto-generated userId
            $userId = mysqli_insert_id($conn);

            // Step 2: Insert data into user_condition
            // Assuming you've already defined $first_time, $hasAllergic, $allergicReaction, etc.
            $query2 = "INSERT INTO user_conditions (first_time, hasAllergic, allergicReaction, isParticipatedSport, sportName, medicalCondition, nailCondition) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $query2);
            mysqli_stmt_bind_param($stmt2, "sssssss", $isFirstTime, $allergicText, $allergicSpecify, $sport, $sportSpecify, $medical_condition, $nail_condition);
            mysqli_stmt_execute($stmt2);

            // Retrieve the auto-generated conditionId
            $conditionId = mysqli_insert_id($conn);

            // Step 3: Insert data into appointment table
            // Assuming you've already defined $referencesNum, $appointmentdate, etc.
            $query3 = "INSERT INTO appointments (referenceNum, userId, conditionId, appointment_date, start_time, end_time, service, status, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt3 = mysqli_prepare($conn, $query3);
            mysqli_stmt_bind_param($stmt3, "siissssss", $referenceNumber, $userId, $conditionId, $appt_date, $start_time, $end_time, $service, $status, $verification_code);

            // 


            // Execute the query
            $result = mysqli_stmt_execute($stmt3);

            if ($result) {
                $data = array(
                    'refNo' => $referenceNumber,
                    'verification_code' => $verification_code,
                    'appt_date' => $appt_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'service' => $service
                );

                $response['data'] = $data;
                $response['success'] = true;
                $response['message'] = "The appointment has been successfully added.";
            } else {
                $response['success'] = false;
                $response['message'] = "Failed to add appointment. Please try again.";
            }

            echo json_encode($response);

            include('./notifications.php');
            notifications_add_data('New Appointment', $referenceNumber, $conn);
            mysqli_stmt_close($stmt1);
            mysqli_stmt_close($stmt2);
            mysqli_stmt_close($stmt3);
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";

    echo json_encode($response);
}

// Send only JSON data