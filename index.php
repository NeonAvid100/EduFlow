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
    $stmt = $conn->prepare("SELECT title, question, deadline, assignment_id FROM assignment WHERE c_name = ? AND id = ? AND status = 'pending'");
    $stmt->bind_param("si", $courseName, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $details['assignments'] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completed_assignments'])) {
    $completedAssignments = $_POST['completed_assignments'];
    foreach ($completedAssignments as $assignmentId) {
        $stmt = $conn->prepare("UPDATE assignment SET status = 'Done' WHERE assignment_id = ?");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
    }
    header("Location: ?course_name=" . urlencode($_GET['course_name']));
    exit;
}

if (isset($_GET['course_name'])) {
    $courseName = $_GET['course_name'];
    $courseDetails = getCourseDetails($conn, $courseName, $userId);
}

function getAssignmentsAndQuizzes($conn, $userId, $date) {
    $stmt = $conn->prepare("
        SELECT a.title, a.deadline AS due_date, a.c_name AS course_name, 'assignment' AS type 
        FROM assignment a 
        JOIN course c ON a.c_name = c.c_name 
        WHERE c.id = ? AND a.deadline = ? AND a.status = 'pending'
        UNION
        SELECT q.q_title AS title, q.q_date AS due_date, q.c_name AS course_name, 'quiz' AS type 
        FROM quiz q 
        JOIN course c ON q.c_name = c.c_name 
        WHERE q.user_id = ? AND q.q_date = ?
    ");
    $stmt->bind_param("isis", $userId, $date, $userId, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$courses = getCourses($conn, $userId);

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$dayAfterTomorrow = date('Y-m-d', strtotime('+2 days'));

$todayTasks = getAssignmentsAndQuizzes($conn, $userId, $today);
$tomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, $tomorrow);
$dayAfterTomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, $dayAfterTomorrow);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <style>
        body {
            display: flex;
        }
        .content {
            width: 70%;
        }
        .reminder {
            width: 30%;
            padding: 20px;
            background-color: #f4f4f4;
            border-left: 1px solid #ccc;
        }
        .reminder h2 {
            margin-top: 0;
        }
        .task-list {
            list-style-type: none;
            padding: 0;
        }
        .task-list li {
            margin-bottom: 10px;
        }
        .course-details form {
            margin-top: 20px;
        }
        .assignments label {
            display: flex;
            align-items: center;
        }
        .assignments input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
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
                <form method="POST">
                    <?php if (count($courseDetails['assignments']) > 0): ?>
                        <ul>
                            <?php foreach ($courseDetails['assignments'] as $assignment): ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="completed_assignments[]" value="<?= $assignment['assignment_id'] ?>">
                                        <strong><?= htmlspecialchars($assignment['title']) ?>:</strong> <?= htmlspecialchars($assignment['question']) ?> (Deadline: <?= htmlspecialchars($assignment['deadline']) ?>)
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No assignments available for this course.</p>
                    <?php endif; ?>
                    <button type="submit">Save Changes</button>
                </form>
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
    <div class="reminder">
        <h2>Reminders</h2>
        <div>
            <h3>Today (<?php echo $today; ?>)</h3>
            <ul class="task-list">
                <?php foreach ($todayTasks as $task): ?>
                    <li><?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Tomorrow (<?php echo $tomorrow; ?>)</h3>
            <ul class="task-list">
                <?php foreach ($tomorrowTasks as $task): ?>
                    <li><?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Day After Tomorrow (<?php echo $dayAfterTomorrow; ?>)</h3>
            <ul class="task-list">
                <?php foreach ($dayAfterTomorrowTasks as $task): ?>
                    <li><?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
