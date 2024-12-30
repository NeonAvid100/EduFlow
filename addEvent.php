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
    $stmt = $conn->prepare("SELECT c_name FROM course WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$courses = getCourses($conn, $userId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventType = $_POST['event_type'];
    $courseName = $_POST['course_name'] ?? null;
    $title = $_POST['title'];
    $question = $_POST['question'];
    $date = $_POST['date'] ?? null;

    if ($eventType == 'course') {
        $description = $_POST['description'];
        $stmt = $conn->prepare("INSERT INTO course (id, c_name, c_description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $title, $description);
    } elseif ($eventType == 'assignment') {
        $stmt = $conn->prepare("INSERT INTO assignment (id, title, question, deadline, c_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $title, $question, $date, $courseName);
    } elseif ($eventType == 'quiz') {
        $stmt = $conn->prepare("INSERT INTO quiz (user_id, q_title, q_description, q_date, c_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $title, $question, $date, $courseName);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input, select, textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Event</h1>
        <form method="POST">
            <label for="event_type">Event Type</label>
            <select id="event_type" name="event_type" required>
                <option value="course">Course</option>
                <option value="assignment">Assignment</option>
                <option value="quiz">Quiz</option>
            </select>

            <div id="course_name_section">
                <label for="course_name">Course Name</label>
                <select id="course_name" name="course_name">
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['c_name']) ?>"><?= htmlspecialchars($course['c_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>

            <label for="question">Question/Description</label>
            <textarea id="question" name="question" rows="4" required></textarea>

            <div id="date_section">
                <label for="date">Date</label>
                <input type="date" id="date" name="date">
            </div>
            <button type="submit">Add Event</button>
        </form>
    </div>

    <script>
        document.getElementById('event_type').addEventListener('change', function() {
            var eventType = this.value;
            var courseNameSection = document.getElementById('course_name_section');
            var dateSection = document.getElementById('date_section');
            var courseDescriptionSection = document.getElementById('course_description_section');
            if (eventType === 'course') {
                courseNameSection.style.display = 'none';
                dateSection.style.display = 'none';
                courseDescriptionSection.style.display = 'block';
                document.getElementById('course_name').disabled = true;
                document.getElementById('date').disabled = true;
            } else {
                courseNameSection.style.display = 'block';
                dateSection.style.display = 'block';
                courseDescriptionSection.style.display = 'none';
                document.getElementById('course_name').disabled = false;
                document.getElementById('date').disabled = false;
            }
        });
    </script>
</body>
</html>