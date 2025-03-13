<?php
include('../utils/connection.php');
session_start();
session_regenerate_id(true);

$response = array();




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] === 'view_again') {
        $ref_input = $_SESSION['view_login'];

        $sql = "SELECT a.*, ui.*, uc.* 
                    FROM appointments AS a
                    LEFT JOIN user_info AS ui ON a.userId = ui.userId
                    LEFT JOIN user_conditions AS uc ON a.conditionId = uc.conditionId
                    WHERE a.referenceNum = ?";

        // Prepare the statement
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $ref_input);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            // If data is found, return it in the response
            $response['success'] = true;
            $response['data'] = $row;
            $_SESSION['view_login'] = $row['referenceNum'];
        } else {
            // If no data is found or an error occurred
            $response['success'] = false;
            $response['message'] = "No information found for the given ID: " . $id;
        }

        echo json_encode($response);
        exit();
    }

    $ref_input = filter_input(INPUT_POST, "ref-input", FILTER_SANITIZE_SPECIAL_CHARS);
    $v_code = filter_input(INPUT_POST, "v-code", FILTER_SANITIZE_SPECIAL_CHARS);
    $errorMsg = "";
    if (!empty($ref_input) || !empty($v_code)) {
        $query = "SELECT * FROM appointments WHERE referenceNum = '$ref_input'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['verification_code'] === $v_code) {
                    $sql = "SELECT a.*, ui.*, uc.* 
                    FROM appointments AS a
                    LEFT JOIN user_info AS ui ON a.userId = ui.userId
                    LEFT JOIN user_conditions AS uc ON a.conditionId = uc.conditionId
                    WHERE a.referenceNum = ?";

                    // Prepare the statement
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $ref_input);

                    // Execute the query
                    mysqli_stmt_execute($stmt);

                    // Get the result
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        // If data is found, return it in the response
                        $response['success'] = true;
                        $response['data'] = $row;
                        $_SESSION['view_login'] = $row['referenceNum'];
                    } else {
                        // If no data is found or an error occurred
                        $response['success'] = false;
                        $response['message'] = "No information found for the given ID: " . $id;
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = "Reference Number and Verification Code doesn't match";
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Reference Number does not exist";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Ref No. and Verification Code is required!";
    }

    echo json_encode($response);
}


function fetch_user_data($conn, $response, $ref_input)
{
    $sql = "SELECT a.*, ui.*, uc.* 
                    FROM appointments AS a
                    LEFT JOIN user_info AS ui ON a.userId = ui.userId
                    LEFT JOIN user_conditions AS uc ON a.conditionId = uc.conditionId
                    WHERE a.referenceNum = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ref_input);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // If data is found, return it in the response
        $response['success'] = true;
        $response['data'] = $row;
        $_SESSION['view_login'] = $row['verification_code'];
    } else {
        // If no data is found or an error occurred
        $response['success'] = false;
        $response['message'] = "No information found for the given ID: " . $id;
    }
}
