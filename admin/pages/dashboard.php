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
    <title>Nailsbykheley - dashboard</title>
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/dashboard.css" />
    <link rel="stylesheet" href="../styles/calendar_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
    <div class="main">

        <div class="main-content">
            <h4>Welcome, <?php echo $_SESSION['username'] ?>!</h4>

            <div class="summary">
                <div class="filter">
                    <select name="date-filter" id="date-filter">
                        <option value="all_of_time">All of Time</option>
                        <option value="today">Today</option>
                        <option value="this_week" selected>This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="this_year">This Year</option>
                    </select>
                </div>
                <div class="box-summary box1">
                    <p>No. of Confirmed Appointment</p>
                    <p class="val">00</p>
                    <i class="fas fa-user-plus"></i>
                </div>

                <div class="box-summary box2">
                    <p>No. of Completed Appointment</p>
                    <p class="val">00</p>
                    <i class="fas fa-user-check"></i>
                </div>

                <div class="box-summary box3">
                    <p>No. of Cancelled Appointment</p>
                    <p class="val">00</p>
                    <i class="fas fa-user-minus"></i>
                </div>
            </div>

            <div class="calendar-section">
                <div class="calender-object">
                    <h5>Calendar</h5>

                    <div class="c-content">

                        <div class="calendar-container">
                            <header class="calendar-header">
                                <p class="calendar-current-date">April 2024</p>
                                <div class="calendar-navigation">
                                    <span id="calendar-prev">
                                        <i class='bx bx-chevron-left'></i>
                                    </span>
                                    <span id="calendar-next">
                                        <i class='bx bx-chevron-right'></i>
                                    </span>
                                </div>
                            </header>

                            <div class="calendar-body">
                                <ul class="calendar-weekdays">
                                    <li>Sun</li>
                                    <li>Mon</li>
                                    <li>Tue</li>
                                    <li>Wed</li>
                                    <li>Thu</li>
                                    <li>Fri</li>
                                    <li>Sat</li>
                                </ul>
                                <ul class="calendar-dates">
                                </ul>
                            </div>
                            <div class="legend">
                                <!-- <p>Legend:</p> -->
                                <div class="pending-lg">
                                    <div></div>Pending
                                </div>
                                <div class="confirmed-lg">
                                    <div></div>Confirmed
                                </div>
                                <div class="fullslot-lg">
                                    <div></div>Fully Booked
                                </div>

                            </div>
                        </div>



                        <div class="appt-view-container">
                            <div class="date-container">
                                <h5>June 16, 2023</h5>
                            </div>
                            <h6>APPOINTMENTS</h6>
                            <div class="appt-list">
                                <div class="appt-item">
                                    <div class="appt-item-info">
                                        <p>APPT-20240605-000090</p>
                                        <p>9:00 AM - 10:00AM</p>
                                        <p>Soft-Gel Extension</p>
                                    </div>
                                    <div class="appt-item-status">Pending</div>
                                </div>

                                <div class="appt-item">
                                    <div class="appt-item-info">
                                        <p>APPT-20240605-000090</p>
                                        <p>9:00 AM - 10:00AM</p>
                                        <p>Soft-Gel Extension</p>
                                    </div>
                                    <div class="appt-item-status">Pending</div>
                                </div>

                                <div class="appt-item">
                                    <div class="appt-item-info">
                                        <p>APPT-20240605-000090</p>
                                        <p>9:00 AM - 10:00AM</p>
                                        <p>Soft-Gel Extension</p>
                                    </div>
                                    <div class="appt-item-status">Pending</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/calendar_dashboard.js"></script>

    <script>
        var date_filter_count_appt = document.querySelector('#date-filter');
        console.log(date_filter_count_appt.value);

        date_filter_count_appt.addEventListener('change', () => print_data(date_filter_count_appt.value));

        document.addEventListener("DOMContentLoaded", function() {
            print_data(date_filter_count_appt.value);

        });

        async function print_data(range) {

            var {
                confirmed,
                completed,
                cancelled,
            } = await Request.get_appointment_count(range);

            document.querySelectorAll('.box-summary .val').forEach((el, index) => {
                let text = [confirmed, completed, cancelled][index];


                el.textContent = text ? String(parseInt(text)).padStart(2, '0') : "00";
            })

        }


        var boxes = document.querySelectorAll('.box-summary');
        var links = ['confirmedAppointment', 'completedAppointment', 'cancelledAppointment'];

        boxes.forEach((box, index) => {
            box.addEventListener('click', () => {
                document.location.href = `./${links[index]}.php`;
            })
        });


        const Request = (function() {
            function get_appointment_count(range) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();

                    const requestBody = 'action=get_appt_count' + '&date_range=' +
                        range; // Serialize array to JSON string
                    xhr.open('POST', '../../api/appointment.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    resolve(response);
                                } else {
                                    reject(response.message || 'An error occurred');
                                }
                            } else {
                                reject('Error occurred while processing your request');
                            }
                        }
                    };

                    xhr.send(requestBody);
                });
            }

            return {
                get_appointment_count
            }
        })();
    </script>
</body>

</html>