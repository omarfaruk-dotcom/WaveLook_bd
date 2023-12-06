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
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $gmail = isset($_POST['gmail']) ? $_POST['gmail'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";

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
            $mail->addAddress($gmail);
            $mail->isHTML(true);
            $mail->Subject = "Order Confirmation from Wave Look";

            // Construct the email message with product information
            $message = "Dear $name,<br><br>";
            $message .= "Thank you for placing an order with Wave Look. Below are the details of your order:<br><br>";

            foreach ($products as $product) {
                $message .= "Product: {$product['name']}<br>";
                $message .= "Quantity: {$product['quantity']}<br>";
                $message .= "Price: {$product['price']}<br>";
                $tp = $product['quantity'] * $product['price'];
                $message .= "Total Price: {$tp}<br><br>";
            }

            $message .= "Delivery Information:<br>";
            $message .= "Name: $name<br>";
            $message .= "Phone: $phone<br>";
            $message .= "Address: $address<br>";

            $mail->Body = $message;

            $mail->send();
            unset($_SESSION['cart']);
            echo "<script>alert('Order sent successfully.');</script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('Your cart is empty.');</script>";
    }
    header("Location: ../shop.html");
}
?>
