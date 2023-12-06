<?php
session_start();

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
    $enteredCode = $_POST['varificationCode'];
    $storedCode = $_SESSION['verification_code'];
    $email = $_POST['email'];

    echo "Debug Output: enteredCode=$enteredCode, storedCode=$storedCode, email=$email";

    if ($enteredCode == $storedCode) {
        // Verification code is correct
        $updateQuery = "UPDATE userinfos SET verified = 1 WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $email);

        if ($updateStmt->execute()) {
            echo "Verification successful! You can now log in.";
            $conn->close();
            header("Location: ../login.html");
            exit();
        } else {
            echo "Error updating verification status: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Incorrect verification code. Please try again.";
    }
}

$conn->close();
?>