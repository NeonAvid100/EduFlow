<?php

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE login SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    echo "Password updated successfully";
}

?>