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

// Fetch assignments, quizzes, and files for a specific course
function getCourseDetails($conn, $courseName, $userId) {
    $details = [];

    // Fetch assignments
    $stmt = $conn->prepare("SELECT title, question, deadline FROM assignment WHERE c_name = ? AND id = ?");
    $stmt->bind_param("si", $courseName, $userId);
    $stmt->execute();
    $details['assignments'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch quizzes
    $stmt = $conn->prepare("SELECT q_title, q_description, q_date FROM quiz WHERE c_name = ? AND user_id = ?");
    $stmt->bind_param("si", $courseName, $userId);
    $stmt->execute();
    $details['quizzes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch files
    $stmt = $conn->prepare("SELECT title, file_name, file_type, file_size, file_path FROM file_storage WHERE c_name = ? AND id = ?");
    $stmt->bind_param("si", $courseName, $userId);
    $stmt->execute();
    $details['files'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $details;
}

$courses = getCourses($conn, $userId);

if (isset($_GET['course_name'])) {
    $courseName = $_GET['course_name'];
    $courseDetails = getCourseDetails($conn, $courseName, $userId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <div class="courses">
            <h2>Your Courses</h2>
            <div class="course-list">
                <?php foreach ($courses as $course): ?>
                    <div class="course-box">
                        <a href="?course_name=<?= urlencode($course['c_name']) ?>">
                            <?= htmlspecialchars($course['c_name']) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (isset($courseDetails)): ?>
        <div class="course-details">
            <h2>Details for Course: <?= htmlspecialchars($_GET['course_name']) ?></h2>

            <div class="assignments">
                <h3>Assignments</h3>
                <?php if (count($courseDetails['assignments']) > 0): ?>
                    <ul>
                        <?php foreach ($courseDetails['assignments'] as $assignment): ?>
                            <li>
                                <strong><?= htmlspecialchars($assignment['title']) ?>:</strong> <?= htmlspecialchars($assignment['question']) ?> (Deadline: <?= htmlspecialchars($assignment['deadline']) ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No assignments available for this course.</p>
                <?php endif; ?>
            </div>

            <div class="quizzes">
                <h3>Quizzes</h3>
                <?php if (count($courseDetails['quizzes']) > 0): ?>
                    <ul>
                        <?php foreach ($courseDetails['quizzes'] as $quiz): ?>
                            <li>
                                <strong><?= htmlspecialchars($quiz['q_title']) ?>:</strong> <?= htmlspecialchars($quiz['q_description']) ?> (Date: <?= htmlspecialchars($quiz['q_date']) ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No quizzes available for this course.</p>
                <?php endif; ?>
            </div>
            <div class="files">
                <h3>Files</h3>
                <?php if (count($courseDetails['files']) > 0): ?>
                    <ul>
                        <?php foreach ($courseDetails['files'] as $file): ?>
                            <li>
                                <strong><?= htmlspecialchars($file['title']) ?>:</strong> <?= htmlspecialchars($file['file_name']) ?> (<?= htmlspecialchars($file['file_type']) ?>, <?= htmlspecialchars($file['file_size']) ?> bytes)
                                <a href="<?= htmlspecialchars($file['file_path']) ?>" download>Download</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No files available for this course.</p>
                <?php endif; ?>
                <a href="upload.php" class="upload-button">Upload Files</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>