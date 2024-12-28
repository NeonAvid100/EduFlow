<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$userId = $_SESSION['user_id']; 

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if your password is set
$dbname = "eduflow";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the file is uploaded
if (isset($_POST['upload'])) {
    $file = $_FILES['file'];
    $title = $_POST['title']; // Get the title from the form

    // Get file details
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Define allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'video/mp4', 'audio/mp3'];

    // Check for errors
    if ($fileError !== 0) {
        echo "There was an error uploading your file.";
        exit;
    }

    // Check file type
    if (!in_array($fileType, $allowedTypes)) {
        echo "Invalid file type. Only JPG, PNG, MP4, and MP3 are allowed.";
        exit;
    }

    // Create a unique file name
    $fileDestination = 'uploads/' . uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

    // Move file to the 'uploads' folder
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        // Insert file details into the database
        $sql = "INSERT INTO file_storage (id, file_name, file_type, file_size, file_path, title) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ississ", $userId, $fileName, $fileType, $fileSize, $fileDestination, $title);

        if ($stmt->execute()) {
            echo "File uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload the file.";
    }
}


// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Storage Page</title>
</head>
<body>
    <h1>Upload Files</h1>

    <!-- File upload form -->
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="title">File Title:</label>
            <input type="text" name="title" id="title" required>
        <br>
        <label for="file">Select a file to upload (JPG, PNG, MP4, MP3, etc.):</label>
            <input type="file" name="file" id="file" required>
        <br>
            <button type="submit" name="upload">Upload</button>
    </form>

</body>
</html>
