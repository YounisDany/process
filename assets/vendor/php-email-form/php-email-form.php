<?php


require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


// SMTP Configuration
$smtp_host = 'smtp.gmail.com'; // Replace with your SMTP host (e.g., smtp.gmail.com for Gmail)
$smtp_username = getenv('EMAIL_USERNAME'); // Retrieve the email username from environment variable
$smtp_password = getenv('EMAIL_PASSWORD'); // Retrieve the email password from environment variable
$smtp_port = 587; // Replace with your SMTP port (e.g., 587 for Gmail)

// Email Details
$to = 'akshayne912@gmail.com'; // Replace with your email address
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$from = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

if (filter_var($from, FILTER_VALIDATE_EMAIL)) {
    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = 'tls'; // Use 'ssl' if required (e.g., for Gmail with port 465)
        $mail->Port = $smtp_port;

        $mail->setFrom($from, $name);
        $mail->addAddress($to);
        $mail->isHTML(false);

        $mail->Subject = $subject;
        $mail->Body = $message . "\r\n\r\nfrom: " . $_SERVER['REMOTE_ADDR'];


        $logMessage = "Email Content:\n";
        $logMessage .= "To: $to\n";
        $logMessage .= "From: $from\n";
        $logMessage .= "Subject: $subject\n";
        $logMessage .= "pass: $smtp_password\n";
        $logMessage .= "Message: $message\n";
        error_log($logMessage);
    


        $mail->send();
        echo 'OK';
    } catch (Exception $e) {
        echo 'Error: ' . $mail->ErrorInfo;
    }
} else {
    echo 'Invalid address';
}
