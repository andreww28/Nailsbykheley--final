<?php

session_start();
session_regenerate_id(true);
if (!isset($_SESSION['username'])) {
    header("location: login.php");
}

if (isset($_POST["logout"])) {
    session_destroy();
    header('location: login.php');
}

include("../sections/admin_nav.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/confirmed_appointment.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
    <title>Document</title>
</head>

<body>
    <div class="main">
        <div class="main-content">
            <h4>Completed Appointments</h4>
            <div class="content">
                <div class="custom-control">
                    <div>
                        <div class="action-btn">
                            <button id="remove-btn">
                                <i class="fas fa-trash"></i>
                                <span>Remove</span>
                            </button>
                            <button id="view-btn">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                        </div>
                        <div class='selection-control'>
                            <button id="selectAll-btn">
                                <span>Select All</span>
                            </button>
                            <button id="deselect-btn">
                                <span>Deselect</span>
                            </button>
                            <button id="reset-filter">
                                <span>Reset Filter</span>
                            </button>
                        </div>
                    </div>

                    <div class="custom-filter-control">
                        <p>Appointment Date</p>
                        <div>
                            <label for="registered_from">From:</label>
                            <input type="date" name="registered_from">
                        </div>
                        <div>
                            <label for="registered_to">To:</label>
                            <input type="date" name="registered_to">
                        </div>
                    </div>

                </div>

            </div>
            <table id="example" class="display stripe" style="width:100%">
                <thead>
                    <tr>
                        <th>Ref No.</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th>Ref No.</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Service</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </div>

    <?php include('../sections/full_info_popup.php') ?>
    <script src="../scripts/pendingAppointment.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script>
        const remove_btn = document.getElementById('remove-btn');

        Init_APPT.set_current_status("completed");
        var table = Table_functions.init_table('#example', "../../api/fetch_appointment.php");
        // Table_functions.set_var_table(table);
        Table_functions.addEvents(table);
        Table_functions.set_var_table(table);

        remove_btn.addEventListener('click', () => Custom_Controls.remove_btn_event("delete_completed",
            "Are you sure you want to permanently remove this appointment data? This action cannot be undone."));
    </script>

</body>

</html>