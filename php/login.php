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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the data (you may add more validation as needed)
    if (empty($email) || empty($password)) {
        echo "Please enter both email and password.";
    } else {
        // Check if the user exists in the database
        $checkQuery = "SELECT * FROM userinfos WHERE email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Verify the password
            if ($row['password'] == $password) {
                // Password is correct, store user data in session
                $_SESSION['user_id'] = $row['id']; // Replace 'id' with your actual primary key column name
                $_SESSION['name'] = $row['name'];

                // Redirect to a dashboard or another page after successful login
                header("Location: ../index.html");
                exit();
            } else {
                header("Location: ../loginagain.html");
                exit();
            }
        } else {
            echo "User not found. Please check your email and try again.";
        }

        $checkStmt->close();
    }
}

$conn->close();
?>
