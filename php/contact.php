<?php
// Start the session to store verification code
session_start();

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "register";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : ""; // Assuming email is collected in the form
    $massage = isset($_POST['massage']) ? $_POST['massage'] : ""; // Assuming massage is part of the form data
    $subject = isset($_POST['subject']) ? $_POST['subject'] : "";

    // Check if the cart session variable exists and is not empty
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Assume that $_SESSION['cart'] contains the product information
        $products = $_SESSION['cart'];

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "wavelook.partners@gmail.com";
            $mail->Password = "rnzyyozgksmtodau";
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('wavelook.partners@gmail.com');
            $mail->addAddress($email); // Use $email instead of undefined $gmail
            $mail->isHTML(true);
            $mail->Subject = "Massage From $name";

            // Construct the email message with product information
            $message = "Dear $name,<br><br>";
            $message .= "Your is on our hand. We will try to contact you soon. <br>Your Massage Is :<br><br>$massage";
            $mail->Body = $message;

            $mail->send();

            echo "<script>alert('Massage sent successfully.');</script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('Your cart is empty.');</script>";
    }
    header("Location: ../index.html");
}
?>
