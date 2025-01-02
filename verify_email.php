<?php

include('db_connection.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo "Invalid email format";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $otp = rand(100000, 999999);

        // Save OTP in the database with expiry time
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $update_stmt = $conn->prepare("UPDATE login SET otp = ?, otp_expiry = ? WHERE email = ?");
        $update_stmt->bind_param("iss", $otp, $expiry, $email);
        $update_stmt->execute();

        // Send OTP to the user's email
        mail($email, "Your OTP Code", "Your OTP code is: $otp");

        echo "success";
    } else {
        echo "Email not found";
    }
}

?>