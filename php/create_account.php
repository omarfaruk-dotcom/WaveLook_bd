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
    // Collect user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['db'];
    $firstpass = $_POST['first_pass'];
    $secpass = $_POST['sec_pass'];

    // Check if email and phone do not exist in the database and are verified
    $checkQuery = "SELECT * FROM userinfos WHERE (email = ? OR phone = ?) AND verified = 1";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        $row = $result->fetch_assoc();
    if ($row['verified'] == 1) {
        // Email or phone number is verified
        echo "Email or phone number already exists in the database and is verified.";
    } else {
        // Email or phone number exists but is not verified
        echo "Email or phone number already exists in the database but is not verified. Deleting the unverified record...";

        // Delete the unverified record
        $deleteQuery = "DELETE FROM userinfos WHERE (email = ? OR phone = ?) AND verified = 0";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ss", $email, $phone);
        
        if ($deleteStmt->execute()) {
            echo "Unverified record deleted successfully.";
        } else {
            echo "Error deleting unverified record: " . $deleteStmt->error;
        }

        $deleteStmt->close();
    }
    $checkStmt->close();
        // Email and phone do not exist in the database
        if ($firstpass == $secpass) {
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
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Verification Code";
                $verificationCode = rand(100000, 999999);

                $_SESSION['verification_code'] = $verificationCode;
                $mail->Body = "Your verification code is: " . $verificationCode;

                $mail->send();

                echo "<script>alert('Verification code sent successfully.');</script>";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            $hashedPassword = $firstpass;

            $verified = 0;

            $insertQuery = "INSERT INTO userinfos (name, email, phone, dob, password, verification_code, verified) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ssssssi", $name, $email, $phone, $dob, $hashedPassword, $verificationCode, $verified);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                $conn->close();
                header("Location: ../varify.html");
                exit();
            } else {
                echo "Error: " . $insertStmt->error;
            }
        } else {
            echo "Passwords do not match.";
        }
    } else {
        echo "Email or phone number already exists in the database or not verified.";
    }

    $checkStmt->close();
}

$conn->close();
?>
