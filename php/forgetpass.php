<?php
// Start the session to access session variables
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

// Initialize variables
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect user input
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['db'];
    $rememberedPassword = $_POST['remembered_password'];

    // Validate the data (you may add more validation as needed)
    if (empty($email) || empty($phone) || empty($dob) || empty($rememberedPassword)) {
        $errorMsg = "Please fill in all the required fields.";
    } else {
        // Check if the user exists in the database
        $checkQuery = "SELECT * FROM userinfos WHERE email = ? AND phone = ? AND dob = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("sss", $email, $phone, $dob);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            if ($row['verified'] == 0) {
                // Set verified to 1 and update the database
                $updateQuery = "UPDATE userinfos SET verified = 1 WHERE email = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("s", $email);

                if ($updateStmt->execute()) {
                    $successMsg = "Verification successful. You can now reset your password.";
                } else {
                    $errorMsg = "Error updating verification status: " . $updateStmt->error;
                }

                $updateStmt->close();
            } else {
                // User is already verified, check the remembered password
                if ($row['password'] == $rememberedPassword) {
                    $successMsg = "You remembered the password. You can now reset it.";

                    // You can implement the logic for allowing the user to reset the password here
                } else {
                    $errorMsg = "Incorrect remembered password. Please try again.";
                }
            }
        } else {
            $errorMsg = "User not found. Please check your information and try again.";
        }

        $checkStmt->close();
    }
}

$conn->close();
?>

