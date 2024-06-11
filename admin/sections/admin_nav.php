<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>Side Navigation Bar in HTML CSS JavaScript</title>
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/admin_nav.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <style>
        .dp-menu form {
            display: flex;
            flex-direction: column;
            gap: 0.4em;
        }

        .dp-menu form button {
            display: flex;
            gap: 1em;
            color: var(--white-color);
            background-color: transparent;
            border: none;
            outline: none;
            align-items: center;
            font-size: 0.8rem;
            color: inherit;
            font-family: inherit;
            cursor: pointer;
        }

        .dp-menu form button:hover {
            color: var(--light-pink);
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar">
        <div class="logo_item">
            <i class="bx bx-menu" id="sidebarOpen"></i>
            <h6 class="menu_title menu_dahsboard title-navbar">Admin Panel</h6>
        </div>

        <div class="navbar_content">
            <i class="bi bi-grid"></i>
            <div class="notif-div">
                <div>
                    <i class='bx bx-bell'></i>
                    <div class="notif-number-div">
                        <span id="notification-count"></span>
                    </div>
                </div>

                <div class="notif-container">
                    <h5>Notifications</h5>
                    <div class="notif-list">
                        <div class="notif-item">
                            <i class="fas fa-user-clock"></i>
                            <h6>New Appointment</h6>
                            <p>You have new pending appointment. <span class="notif-refNo">APPT-20241023-213</span></p>
                            <p class="notif-date">June 3, 8:30 am</p>
                            <div class=" read-indicator">
                            </div>
                            <i class="fas fa-times remove-btn-notif"></i>
                        </div>
                        <div class="notif-item cancel">
                            <i class="fas fa-user-minus"></i>
                            <h6>Cancel Appointment</h6>
                            <p><span class="notif-refNo">APPT-20241023-213</span> cancelled his/her appointment.</p>
                            <p class="notif-date">June 3, 8:30 am</p>

                            <div class=" read-indicator">
                            </div>
                            <i class="fas fa-times remove-btn-notif"></i>
                        </div>
                    </div>
                    <div class="notif-action-container">
                        <button id="notif-mark-btn">Mark all as read</button>
                        <button id="notif-clear-all">Clear all</button>
                    </div>
                </div>
            </div>
            <div class="dropdown-profile">
                <div class="profile-container">
                    <img src="../assets/img/admin-profile.jpg" alt="" class="profile" />
                    <i class='bx bx-chevron-down'></i>
                </div>

                <div class="dp-menu">
                    <p>Admin</p>
                    <form action=<?php echo $_SERVER["PHP_SELF"] ?> method="post">
                        <button type="button" id="change-pass-btn">
                            <i class='bx bxs-lock-open-alt'></i>
                            <p>Change Password</p>
                        </button>
                        <button type="submit" name="logout">
                            <i class='bx bxs-log-out'></i>
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- sidebar -->
    <nav class="sidebar">
        <div class="menu_content">
            <ul class="menu_items">
                <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
                <!-- start -->

                <li class="item active1">
                    <a href="../pages/dashboard.php" class="nav_link">
                        <span class="navlink_icon">
                            <i class='bx bxs-dashboard'></i>
                        </span>
                        <span class="navlink">Dashboard</span>
                    </a>
                </li>

                <li class="item">
                    <div href="#" class="nav_link submenu_item active2">
                        <span class="navlink_icon">
                            <i class='bx bxs-time'></i>
                        </span>
                        <span class="navlink">Appointments</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                    </div>

                    <ul class="menu_items submenu">
                        <a href="../pages/pendingAppointment.php" class="nav_link sublink">Pending</a>
                        <a href="../pages/confirmedAppointment.php" class="nav_link sublink">Confirmed</a>
                        <a href="../pages/completedAppointment.php" class="nav_link sublink">Completed</a>
                        <a href="../pages/cancelledAppointment.php" class="nav_link sublink">Cancelled</a>
                    </ul>
                </li>

                <li class="item active3">
                    <a href="../pages/schedule.php" class="nav_link">
                        <span class="navlink_icon">
                            <i class='bx bxs-calendar-event'></i>
                        </span>
                        <span class="navlink">Schedule</span>
                    </a>
                </li>

                <li class="item">
                    <div href="#" class="nav_link submenu_item active4">
                        <span class="navlink_icon">
                            <i class='bx bxs-photo-album'></i>
                        </span>
                        <span class="navlink">Gallery</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                    </div>

                    <ul class="menu_items submenu">
                        <a href="../pages/gallery.php" class="nav_link sublink">List</a>
                        <a href="../pages/theme.php" class="nav_link sublink">Theme</a>
                    </ul>
                </li>

                <li class="item active5">
                    <a href="../pages/highlights.php" class="nav_link">
                        <span class="navlink_icon">
                            <i class='bx bxs-star'></i>
                        </span>
                        <span class="navlink">Highlights</span>
                    </a>
                </li>
            </ul>


            <!-- Sidebar Open / Close -->
            <div class="bottom_content">
                <div class="bottom expand_sidebar">
                    <span> Expand</span>
                    <i class='bx bx-log-in'></i>
                </div>
                <div class="bottom collapse_sidebar">
                    <span> Collapse</span>
                    <i class='bx bx-log-out'></i>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal-pass">
        <div class="modal-wrapper">
            <i class='bx bx-x' id="close-btn"></i>

            <div class="content">
                <h5>Change Password</h5>
                <form>
                    <div>
                        <label for="old_pass">Current Password</label>
                        <input type="password" name="old_pass" id="old_pass">
                    </div>
                    <div>
                        <label for="old_pass">New Password</label>
                        <input type="password" name="new_pass" id="new_pass">
                    </div>
                    <div>
                        <label for="old_pass">Confirm Password</label>
                        <input type="password" name="confirm_pass" id="confirm_pass">
                    </div>
                    <p id="error-p" style="color: red; display: none;">All fields are required!</p>
                    <button type="submit" name="change" id="change-btn">Change</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../scripts/admin_nav.js"></script>
</body>

</html>