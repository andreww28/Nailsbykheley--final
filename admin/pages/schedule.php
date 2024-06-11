<?php
include('../../utils/connection.php');
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

$query = "SELECT * FROM available_time ORDER BY from_time";
$result = mysqli_query($conn, $query);
$available_time = mysqli_fetch_all($result, MYSQLI_ASSOC);

function removeOutdatedEntries($conn)
{
    $today = date('Y-m-d');
    $query = "DELETE FROM off_days WHERE to_date < '$today'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

removeOutdatedEntries($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme - ADMIN</title>
    <link rel="stylesheet" href="../styles/schedule.css" />
</head>

<body>
    <div class="main">
        <div class="main-content">
            <h4>Schedule</h4>

            <div class="content-div">
                <div class="time-content">
                    <h6>Available Time</h6>
                    <form class="time-action-div">
                        <div class="time-input-div">
                            <div class="from-time-div">
                                <label for="from-time">From:</label>
                                <input type="time" id="from-time" name="from-time">
                            </div>
                            <div class="to-time-div">
                                <label for="to-time">To:</label>
                                <input type="time" id="to-time" name="to-time">
                            </div>
                        </div>
                        <button type="submit" id="time-new-btn">
                            <i class='bx bx-plus-medical'></i>
                            <span>Add</span>
                        </button>
                    </form>

                    <div class="time-table-wrapper">
                        <table id="time-table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="date-content">
                    <h6>Off Days</h6>
                    <div class="date-action-div">
                        <form class="date-select">
                            <div class="date-input-div">
                                <div class="from-date-div">
                                    <label for="from-date">From:</label>
                                    <input type="date" id="from-date" name="from-date"
                                        min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="to-date-div">
                                    <label for="to-date">To:</label>
                                    <input type="date" id="to-date" name="to-date" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <button type="submit" id="date-new-btn">
                                <i class='bx bx-plus-medical'></i>
                                <span>Add</span>
                            </button>
                        </form>

                        <div class="filter-date">
                            <h6>Filter</h6>
                            <div>
                                <label for="month-filter">Month:</label>
                                <select id="month-filter" name="month-filter">
                                    <option value="1">January</option>
                                    <option value="2">Febraury</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="date-table-wrapper">
                        <table id="date-table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/schedule.js"></script>

</body>

</html>

<?php
mysqli_close($conn);
?>