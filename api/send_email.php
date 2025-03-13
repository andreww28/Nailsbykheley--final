<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
$response = array();

$dotenv = Dotenv\Dotenv::createImmutable("./..");
$dotenv->load();


function send_email($mail, $recipient, $r_name, $subject, $body, $single_email)
{
    $mail->addAddress($recipient, $r_name);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();

    header('Content-Type: application/json');
    $response['success'] = true;
    $response['message'] = "Email sent!";


    if ($single_email) {
        echo json_encode($response);
        exit(); // Stop further execution
    }
}


function initialize_mail($mail)
{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
    $mail->Password = $_ENV['PERSONAL_GMAIL_PASSWORD'];
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;

    return $mail;
}


function send_confirmation_content($mail, $data)
{
    $terms_conditions_note = "<p><strong>Note:</strong></p>
                          <ol>
                              <li>We do not tolerate late clients. Cancel booking if you are 15 minutes late.</li>
                              <li>No show will result in 50% of the service booked.</li>
                              <li>No show for the second time will no longer be able to book at Nailsbykheley.</li>
                          </ol>";

    $subject = "APPOINTMENT CONFIRMATION - NAILSBYKHELEY";
    $body = "<p>Dear " . $data['fullName'] . ",</p>
    <p>We are thrilled to confirm that your appointment with NailsByKheley has been approved! Here are the details:</p>
    <ul>
        <li>Reference Num: " . $data['referenceNum'] . "</li>
        <li>Appointment Date: " . $data['appointment_date'] . "</li>
        <li>Appointment Time: " . date("g:i A", strtotime($data['start_time'])) . " - " . date("g:i A", strtotime($data['end_time'])) . "</li>
        <li>Service: " . $data['service'] . "</li>
        <li>Location: Mt. Apo st. Demesa Compound Brgy Gulang-Gulang Lucena City</li>
    </ul>
    <p>We look forward to welcoming you on " . $data['appointment_date'] . ". If you have any questions or need to make any changes to your appointment, please don\'t hesitate to contact us.</p>
    <p>Thank you for choosing NailsByKheley. We can\'t wait to provide you with a wonderful experience!</p>
    <p>Warm regards, <br>NailsByKheley Team</p>" . $terms_conditions_note;
    $r_name = $data['fullName'];


    send_email($mail, $data['email'], $r_name, $subject, $body, false);
}

function send_cancellation_content($mail, $data)
{
    $subject = "APPOINTMENT CANCELLATION - NAILSBYKHELEY";
    $body = "<p>Dear " . $data['fullName'] . ",</p>
                        <p>We regret to inform you that your appointment with NailsByKheley has been cancelled.</p>
                        <p>If you have any questions or concerns, please feel free to contact us.</p>
                        <p>We apologize for any inconvenience caused.</p>
                        <p>Best regards,<br>NailsByKheley Team</p>";
    $r_name = $data['fullName'];


    send_email($mail, $data['email'], $r_name, $subject, $body, false);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mail = new PHPMailer(true);

    try {
        $mail = initialize_mail($mail);

        if ($_POST['action'] === "send_from_home") {
            $subject = $_POST['subject'];
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';
            $body = '<h1><span style="color:#DE6C99">NAILSBYKHELEY</span> - MESSAGE</h1><p><span><b>From: </b></span>' . $_POST['email'] . '</p><p><span><b>Message: </b></span>' . $_POST['message'] . '</p>';

            send_email($mail, $recipient, $r_name, $subject, $body, true);
        } else if ($_POST['action'] === 'send_from_appt_form') {
            $subject = "New Appointment Submission - NAILSBYKHELEY";
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';

            $body = "<p>Hi ADMIN,</p><h4>A new pending appointment request has been submitted:</h4><ul><li><strong>Reference Number: </strong>" . $_POST['refNo'] . "</li><li><strong>Appointment Date: </strong>" . $_POST['appt_date'] . "</li><li><strong>Time: </strong>" . $_POST['start_time'] . ' - ' . $_POST['end_time'] . "</li><li><strong>Service: </strong>" . $_POST['service'] . "</li></ul><p>Please review and follow up with the customer as needed.</p>";

            send_email($mail, $recipient, $r_name, $subject, $body, true);
        } else if ($_POST['action'] === 'send_from_view_appt') {
            $subject = "Appointment Cancellation - NAILSBYKHELEY";
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';
            $body = "<p>Hi ADMIN,</p><h4>We wanted to inform you that the following appointment has been canceled:</h4><ul><li><strong>Reference Number: </strong>"  . $_POST['refNo'] . "</li><li><strong>Customer: </strong>" . $_POST['name'] . "</li><li><strong>Contact Number: </strong>" . $_POST['mnumber']  . "</li><li><strong>Email: </strong>" . $_POST['email'] . "</li></ul><p>Please consider reaching out to the customer for any follow-up.</p>";

            send_email($mail, $recipient, $r_name, $subject, $body, true);
        } else if ($_POST['action'] === 'send_from_admin') {
            $data = json_decode($_POST['data']);


            if ($_POST['user_action'] === "confirm_pending") {
                for ($i = 0; $i < sizeof($data); $i++) {
                    $appointment_data = (array) $data[$i]; // Convert object to array
                    send_confirmation_content($mail, $appointment_data);
                }
            } else if ($_POST['user_action'] === "delete_confirmed") {
                for ($i = 0; $i < sizeof($data); $i++) {
                    $appointment_data = (array) $data[$i]; // Convert object to array
                    send_cancellation_content($mail, $appointment_data);
                }
            }

            echo json_encode($response);
            exit();
        }
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => "Message didn't send!",
            'error' => $e->getMessage() // Optionally include the error message
        ];
        header('Content-Type: application/json');

        echo json_encode($response);
    }
}
