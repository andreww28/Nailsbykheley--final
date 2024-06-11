<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
$response = array();

$dotenv = Dotenv\Dotenv::createImmutable("./..");
$dotenv->load();


function send_email($mail, $recipient, $r_name, $subject, $body)
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
    echo json_encode($response);
    exit(); // Stop further execution
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mail = new PHPMailer(true);

    try {
        $mail = initialize_mail($mail);

        if ($_POST['action'] === "send_from_home") {
            $subject = $_POST['subject'];
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';
            $body = '<h1><span style="color:#DE6C99">NAILSBYKHELEY</span> - MESSAGE</h1><p><span><b>From: </b></span>' . $_POST['email'] . '</p><p><span><b>Message: </b></span>' . $_POST['message'] . '</p>';

            send_email($mail, $recipient, $r_name, $subject, $body);
        } else if ($_POST['action'] === 'send_from_appt_form') {
            $subject = "New Appointment Submission - NAILSBYKHELEY";
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';

            $body = "<p>Hi ADMIN,</p><h4>A new pending appointment request has been submitted:</h4><ul><li><strong>Reference Number: </strong>" . $_POST['refNo'] . "</li><li><strong>Appointment Date: </strong>" . $_POST['appt_date'] . "</li><li><strong>Time: </strong>" . $_POST['start_time'] . ' - ' . $_POST['end_time'] . "</li><li><strong>Service: </strong>" . $_POST['service'] . "</li></ul><p>Please review and follow up with the customer as needed.</p>";

            send_email($mail, $recipient, $r_name, $subject, $body);
        } else if ($_POST['action'] === 'send_from_view_appt') {
            $subject = "Appointment Cancellation - NAILSBYKHELEY";
            $recipient = $_ENV['PERSONAL_GMAIL_ACCOUNT'];
            $r_name = 'nailsbykheley';
            $body = "<p>Hi ADMIN,</p><h4>We wanted to inform you that the following appointment has been canceled:</h4><ul><li><strong>Reference Number: </strong>"  . $_POST['refNo'] . "</li><li><strong>Customer: </strong>" . $_POST['name'] . "</li><li><strong>Contact Number: </strong>" . $_POST['mnumber']  . "</li><li><strong>Email: </strong>" . $_POST['email'] . "</li></ul><p>Please consider reaching out to the customer for any follow-up.</p>";

            send_email($mail, $recipient, $r_name, $subject, $body);
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
