<?php

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ? AND otp = ? AND otp_expiry >= NOW()");
    $stmt->bind_param("si", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo "otp_verified";
    } else {
        echo "Invalid or expired OTP";
    }
}

?>