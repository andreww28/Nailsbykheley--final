<?php
include('../utils/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['date'])) {
        $date = $_POST['date'];

        // Query to get all available times
        $sqlAvailableTimes = "SELECT from_time, to_time FROM available_time";
        $resultAvailableTimes = mysqli_query($conn, $sqlAvailableTimes);
        $availableTimes = [];
        while ($row = mysqli_fetch_assoc($resultAvailableTimes)) {
            $availableTimes[] = $row['from_time'] . ' - ' . $row['to_time'];
        }

        // Query to get all occupied times for the date
        $sqlOccupiedTimes = "SELECT start_time, end_time FROM appointments WHERE appointment_date = ? AND status='confirmed'";
        $stmt = mysqli_prepare($conn, $sqlOccupiedTimes);
        mysqli_stmt_bind_param($stmt, 's', $date);
        mysqli_stmt_execute($stmt);
        $resultOccupiedTimes = mysqli_stmt_get_result($stmt);

        $occupiedTimes = [];
        while ($row = mysqli_fetch_assoc($resultOccupiedTimes)) {
            $occupiedTimes[] = $row['start_time'] . ' - ' . $row['end_time'];
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo json_encode(['availableTimes' => $availableTimes, 'occupiedTimes' => $occupiedTimes]);
    } else if (isset($_POST['month'])) {
        $month = $_POST['month'];
        $full_slot_day = [];

        // Fetch distinct appointment dates for the given month
        $query = "SELECT DISTINCT appointment_date FROM appointments WHERE status='confirmed' AND Month(appointment_date) = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $day = $row['appointment_date'];

            // Fetch all schedule slots for the given day
            $query_slots = "SELECT from_time, to_time FROM available_time";
            $result_slots = mysqli_query($conn, $query_slots);
            $all_slots_occupied = true;

            while ($row_slot = mysqli_fetch_assoc($result_slots)) {
                $from_time = $row_slot['from_time'];
                $end_time = $row_slot['to_time'];

                // Check if there is any appointment that occupies this slot
                $query_appointment = "
            SELECT 1 
            FROM appointments 
            WHERE appointment_date = ? 
            AND start_time = ? 
            AND end_time = ? 
            AND status = 'confirmed'
        ";
                $stmt_appointment = mysqli_prepare($conn, $query_appointment);
                mysqli_stmt_bind_param($stmt_appointment, 'sss', $day, $from_time, $end_time);
                mysqli_stmt_execute($stmt_appointment);
                $result_appointment = mysqli_stmt_get_result($stmt_appointment);

                if (mysqli_num_rows($result_appointment) == 0) {
                    // Found a slot that is not occupied
                    $all_slots_occupied = false;
                    break;
                }
            }

            if ($all_slots_occupied) {
                $full_slot_day[] = $day;
            }
        }

        echo json_encode(['full_slot_days' => $full_slot_day]);
    }
}
