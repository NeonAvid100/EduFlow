<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduflow";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

// Fetch courses for the logged-in user
function getCourses($conn, $userId) {
    $stmt = $conn->prepare("SELECT c_id, c_name FROM course WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$courses = getCourses($conn, $userId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $courseName = $_POST['course_name'];
    $file = $_FILES['file'];

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'pptx', 'webp');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'uploads/' . $fileNameNew;

                // Move file to the 'uploads' folder
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert file details into the database
                    $sql = "INSERT INTO file_storage (id, file_name, file_type, file_size, file_path, title, c_name) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ississs", $userId, $fileNameNew, $fileType, $fileSize, $fileDestination, $title, $courseName);

                    if ($stmt->execute()) {
                        echo "<script>alert('File uploaded successfully!'); window.location.href='index.php';</script>";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Failed to upload the file.";
                }
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }
    } else {
        echo "You cannot upload files of this type!";
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
        <label for="course_name">Select Course:</label>
        <select name="course_name" id="course_name" required>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course['c_name']) ?>"><?= htmlspecialchars($course['c_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="file">Choose File:</label>
        <input type="file" name="file" id="file" required>
        <br>
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>