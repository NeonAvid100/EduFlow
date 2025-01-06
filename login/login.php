<?php
session_start(); // Start the session to store user data

// Database connection details
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "eduflow";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if the email and password are not empty
    if (empty($email) || empty($password)) {
        header("Location: login/login.html?error=emptyfields");
        exit();
    }

    // Prepare SQL to check if email exists
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session and redirect to the home page
            $_SESSION['user_id'] = $row['id']; // Assuming 'id' is the user identifier
            header("Location: ../index.php");
            exit();
        } else {
            // Incorrect password
            header("Location: login/login.html?error=wrongpassword");
            exit();
        }
    } else {
        // User not found
        header("Location: login/login.html?error=usernotfound");
        exit();
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
