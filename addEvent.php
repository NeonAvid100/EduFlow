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
</head>
<body>
<div class="container-xxl py-5" id="add-event">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Add Event</h5>
            <h1 class="mb-5">Create a New Event</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <label for="event_type"></label>
                                    <select id="event_type" name="event_type" required class="form-control" required>
                                        <option value="course">Course</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="quiz">Quiz</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" id="course_name_section">
                                <div class="form-floating">
                                    <label for="course_name">Course Name</label>
                                    <select id="course_name" name="course_name" class="form-control">
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= htmlspecialchars($course['c_name']) ?>"><?= htmlspecialchars($course['c_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <label for="question">Question/Description</label>
                                    <textarea id="question" name="question" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="col-12" id="date_section">
                                <div class="form-floating">
                                    <label for="date">Date</label>
                                    <input type="date" id="date" name="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button class="btn btn-primary-gradient rounded-pill py-3 px-5" type="submit">Add Event</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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