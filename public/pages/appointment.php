<?php
session_start();


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (isset($data['action'])) {
    if ($data['action'] === 'set_session') {
        if (isset($data['value'])) {
            // Set the session variable
            $_SESSION[$data['key']] = $data['value'];

            // Return a response
            $response = ['status' => 'success', 'message' => 'session value set'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit; // Optionally exit to prevent further output
        }
    } else if ($data['action'] === 'check_tnc_agree') {
        if (isset($data['val'])) {
            if ($data['key'] === 'tnc_agree') {
                $msg = $_SESSION[$data['key']];
            } else {
                $msg = !isset($_SESSION[$data['key']]) || empty(($_SESSION[$data['key']])) ? 'null' : $_SESSION[$data['key']];
            }
            $response = ['status' => 'success', 'message' => $msg, 'key' => $data['key']];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit; // Optionally exit to prevent further output
        }
    } else if ($data['action'] === 'unset_session') {
        unset($_SESSION[$data['key']]);
        $response = ['status' => 'success', 'message' => "success"];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}




function getDisplay()
{
    $tnc_agree = isset($_SESSION['tnc_agree']) && $_SESSION['tnc_agree'] === 'true';
    // Define the style attribute based on whether the user has agreed to the terms and conditions
    return $tnc_agree ? 'display: none;' : '';
}
?>

<!DOCTYPE html>
<!-- Created By CodingNepal - www.codingnepalweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>NAILSBYKHELEY - ABOUT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/appointment.css">
    <link rel="stylesheet" href="../styles/appointment_f.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>

    <!-- Start of navigation -->
    <?php include('../sections/nav.php') ?>

    <!-- End of navigation -->

    <!-- Start of main content -->
    <main>

        <section class="banner">
            <div class="banner-content">
                <h2>Appointment</h2>
                <p>Welcome to our booking page! To schedule your soft gel extension or removal service, please review
                    and agree to our terms and conditions by clicking the button below. If you need assistance, call us
                    at (+63)936 372 3865. </p>
            </div>
        </section>

        <section class="main-content">
            <div class="tab-section">
                <div id="book-apt">
                    <p>Book Appointment</p>
                </div>
                <div id="view-apt">
                    <p>View Appointment</p>
                </div>
            </div>


            <?php
            if (isset($_SESSION['view_login'])) {
                echo '<div class="view-appointment-login" style="display:none">';
            } else {
                echo '<div class="view-appointment-login" style="display:flex">';
            }
            ?>

            <p>Enter the corresponding information after you book an appointment.</p>
            <form action="post" id='view-apt-form'>
                <div>
                    <label for="ref-input">Reference No.:</label>
                    <input type="text" id="ref-input" name="ref-input" value="" placeholder="APPT-" required>
                </div>
                <div>
                    <label for="code">Verification Code:</label>
                    <input type="text" id="code" name="v-code" value="" required>
                </div>
                <button type="submit" name='submit'>Submit</button>
            </form>
            </div>

            <?php
            if (isset($_SESSION['view_login'])) {
                echo '<div class="all-apt-info" style="display:flex">';
            } else if (!isset($_SESSION['view_login']) || empty($_SESSION['view_login'])) {
                echo '<div class="all-apt-info" style="display:none">';
            }
            ?>

            <div class="imp-info">
                <h5>APPT-20240525-000074</h5>
                <button id="logout-btn">Logout</button>
                <h6>Status: <span>Pending</span></h6>
            </div>

            <div class="sched-info">
                <h6>Service & Schedule</h6>
                <div class="content">

                    <p>Date:</p>
                    <p>2024-05-28</p>

                    <p>Time:</p>
                    <p>6:06 AM - 7:06 AM</p>

                    <p>Service</p>
                    <p>Soft-gel Extension</p>
                </div>
            </div>
            <div class="other-info">
                <h6>Other Info:</h6>
                <div class="content">
                    <p>Name:</p>
                    <p>John Andrew San Victores</p>

                    <p>Address:</p>
                    <p>Brgy. Bataan Sampaloc, Quezon</p>

                    <p>Email:</p>
                    <p>johnandrewsanvictores@gmail.com</p>

                    <p>Mobile No.:</p>
                    <p>09167003378</p>

                    <p>First Time of Manicure/Pedicure:</p>
                    <p>No</p>

                    <p>Experienced allergic reactions or irritations from nail or skin products:</p>
                    <p>No</p>

                    <p>Engage in hands-on hobbies or sports activities:</p>
                    <p>No</p>

                    <p>Medical/Skin Condition:</p>
                    <p>N/A</p>

                    <p>Nail Condition:</p>
                    <p>N/A</p>
                </div>
            </div>

            <button>Cancel Booking</button>
            </div>

            <?php include('../sections/appointment_form.php') ?>
        </section>
    </main>

    <!-- End of main content -->
    <div id="snackbar">Content copied to clipboard</div>
    <div class="submit-popup">
        <div class="submit-popup-content">
            <div class="confirm-div-info">
                <p>Appointment Confirmation</p>
                <p>Thank you for your booking request! Your appointment details have been received and are currently
                    being processed. </p>

                <div class="important-info">
                    <p>Your Reference Number: </p>
                    <p id="appt-val"><span class="val">APPT-20240603-000082</span> <i class="fas fa-copy"></i>
                    </p>
                    <p>Your Verification Code:</p>
                    <p id="v-code-val"><span class="val">63fd95e0787f50b4fc48e7844a902572</span> <i class="fas fa-copy"></i></p>
                    <p><span>Note:<span> Please save these details to manage your appointment, including cancellation.
                    </p>
                </div>
            </div>

            <details>
                <summary>
                    Payment
                </summary>
                <div>
                    <p>A ₱100 down payment is required within 24 hours to confirm your appointment.</p>
                    <div class="gcash-info">
                        <p>GCash Information:</p>
                        <div class="gcash-info-content">
                            <img src="../assets/img/logo.jpg" alt="">
                            <div class="info-gcash">
                                <p>Name: <span class="bold">John Andrew San Victores</span></p>
                                <p>Number: <span class="bold">09167003378</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <p>We'll notify you by email or SMS once your appointment is confirmed. For questions or assistance, please
                contact us.</p>
            <button id="ok-btn-submit-popup">
                OK
            </button>
        </div>
    </div>

    <?php include('../sections/tnc.php') ?>

    <?php include('../sections/footer.php') ?>

    <!-- Appointment Form -->
    <script src="../scripts/calendar.js"></script>
    <script src="../scripts/appointment_form.js"></script>

    <script src="../scripts/appointment.js">

    </script>
</body>

</html>