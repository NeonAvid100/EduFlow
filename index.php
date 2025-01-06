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

// Fetch courses for the logged-in user
function getCourses($conn, $userId) {
    $stmt = $conn->prepare("SELECT c_id, c_name FROM course WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deletePastQuizzes($conn) {
    $currentDate = date('Y-m-d');
    $stmt = $conn->prepare("DELETE FROM quiz WHERE q_date < ?");
    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $stmt->close(); // Close the statement to free resources
}

// Execute deletePastQuizzes function
deletePastQuizzes($conn);

$userId = $_SESSION['user_id'];

// Fetch assignments, quizzes, and files for a specific course
if (isset($_GET['course_name'])) {
    $courseName = $_GET['course_name'];

    // Fetch course details
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
        $currentDate = date('Y-m-d');
        $stmt = $conn->prepare("SELECT q_title, q_description, q_date FROM quiz WHERE c_name = ? AND user_id = ? AND q_date >= ?");
        $stmt->bind_param("sis", $courseName, $userId, $currentDate);
        $stmt->execute();
        $details['quizzes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        // Fetch files
        $stmt = $conn->prepare("SELECT title, file_name, file_type, file_size, file_path FROM file_storage WHERE c_name = ? AND id = ?");
        $stmt->bind_param("si", $courseName, $userId);
        $stmt->execute();
        $details['files'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
        return $details;
    }

    $courseDetails = getCourseDetails($conn, $courseName, $userId);

    // Handle course deletion
    if (isset($_POST['delete_course'])) {
        $stmt = $conn->prepare("DELETE FROM assignment WHERE c_name = ?");
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM quiz WHERE c_name = ?");
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM course WHERE c_name = ?");
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Course and related assignments and quizzes deleted successfully!'); window.location.href='index.php';</script>";
    } 
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

$name = "";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's name from the database
    $sql = "SELECT name FROM login WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id); // Bind the session ID
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name']; // Fetch the name into the $name variable
    }

    $stmt->close();
}

$courses = getCourses($conn, $userId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventType = $_POST['event_type'];
    $courseName = $_POST['course_name'] ?? null;
    $title = $_POST['title'];
    $question = $_POST['question'];
    $date = $_POST['date'] ?? null;

    if ($eventType == 'course') {
        $description = $_POST['question'];
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
}

function getCurricularActivities($conn, $userId) {
    $activities = [];
    
    // Fetch the curricular activities from the database
    $stmt = $conn->prepare("SELECT title, description, t_date, t_link FROM co_curriculars WHERE id = ? AND t_date >= CURDATE() ORDER BY t_date ASC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Store the results in an array
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    
    $stmt->close();
    
    return $activities;
}



$courses = getCourses($conn, $userId);

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$dayAfterTomorrow = date('Y-m-d', strtotime('+2 days'));

$todayTasks = getAssignmentsAndQuizzes($conn, $userId, $today);
$tomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, $tomorrow);
$dayAfterTomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, $dayAfterTomorrow);

$assignment_sql = "SELECT COUNT(*) AS total_assignments FROM assignment WHERE id = '$user_id' AND status = 'pending'";
$assignment_result = $conn->query($assignment_sql);
$assignments = $assignment_result->fetch_assoc();
$total_assignments = $assignments['total_assignments'];

// Query to fetch pending quizzes
$quiz_sql = "SELECT COUNT(*) AS total_quizzes FROM quiz WHERE user_id = '$user_id'";
$quiz_result = $conn->query($quiz_sql);
$quizzes = $quiz_result->fetch_assoc();
$total_quizzes = $quizzes['total_quizzes'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>EduFlow</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Jost:wght@500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="51">
    <div class="container-xxl bg-white p-0" style="background-color: white;">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0" id="home">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="m-0">EduFlow</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="#home" class="nav-item nav-link active">Dashboard</a>
                        <a href="#about" class="nav-item nav-link">Courses</a>
                        <a href="#feature" class="nav-item nav-link">Reminders</a>
                        <a href="#pricing" class="nav-item nav-link">Events</a>
                        <a href="#review" class="nav-item nav-link">Add</a>
                    </div>
                    <a href="logout.php" onclick="event.preventDefault(); logout();" class="btn btn-primary-gradient rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Log Out</a>
                    <script>
                        function logout() {
                            window.location.href = "index.html";
                        }
                    </script>

                </div>
            </nav>

            <div class="container-xxl bg-primary hero-header">
      <div class="container px-lg-5">
          <div class="row g-5">
              <div class="col-lg-8 text-center text-lg-start">
                  <h1 class="text-white mb-4 animated slideInDown">
                  Welcome To Your Dashboard, <?php echo htmlspecialchars($name); ?>!
                  </h1>
                  <div class="row g-4 mb-4">
    <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
        <div class="d-flex">
            <i class="bi bi-journal-plus fa-2x text-primary-gradient flex-shrink-0 mt-1" style="-webkit-text-fill-color: white;"></i>
            <div class="ms-3">
                <h2 class="mb-0" id="pending-assignments" data-toggle="counter-up">0</h2>
                <p class="text-primary-gradient mb-0" style="-webkit-text-fill-color: white;">Pending Assignments</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
        <div class="d-flex">
            <i class="bi bi-pencil-square fa-2x text-secondary-gradient flex-shrink-0 mt-1" style="-webkit-text-fill-color: white;"></i>
            <div class="ms-3">
                <h2 class="mb-0" id="pending-quizzes" data-toggle="counter-up">0</h2>
                <p class="text-secondary-gradient mb-0" style="-webkit-text-fill-color: white;">Pending Quizzes</p>
            </div>
        </div>
    </div>
</div>

        <script>
        // Use the fetched data to update the HTML
        document.getElementById('pending-assignments').innerText = "<?php echo $total_assignments; ?>";
        document.getElementById('pending-quizzes').innerText = "<?php echo $total_quizzes; ?>";
        </script>

              </div>
              <div class="col-lg-4 d-flex justify-content-center justify-content-lg-end wow fadeInUp" data-wow-delay="0.3s">
                  <div class="owl-carousel screenshot-carousel">
                            <img class="img-fluid" src="images/screenshot-1.png" alt="">
                            <img class="img-fluid" src="images/screenshot-2.png" alt="">
                            <img class="img-fluid" src="images/screenshot-3.png" alt="">
                            <img class="img-fluid" src="images/screenshot-4.png" alt="">
                            <img class="img-fluid" src="images/screenshot-5.png" alt="">
                  </div>
              </div>
          </div>
      </div>
  </div>
<div class="container-xxl py-5" id="about">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Your Dashboard</h5>
            <h1 class="mb-5">Your Courses</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <div class="row g-4">
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-4">
                        <div class="bg-light rounded border">
                            <div class="border-bottom p-4 mb-4">
                                <h4 class="text-primary-gradient mb-1"><?= htmlspecialchars($course['c_name']) ?></h4>
                            </div>
                            <div class="p-4 pt-0">
                                <a href="?course_name=<?= urlencode($course['c_name']) ?>" class="btn btn-primary-gradient rounded-pill py-2 px-4 mt-4">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($courseDetails)): ?>
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <div class="course-details">
            <h2>Details for Course: <?= htmlspecialchars($_GET['course_name']) ?></h2>
            <div class="assignments">
                <h3 class="text-primary-gradient">Assignments</h3>
                <form method="POST">
                    <?php if (count($courseDetails['assignments']) > 0): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($courseDetails['assignments'] as $assignment): ?>
                        <li class="d-flex justify-content-between mb-3">
                            <label class="checkbox-container" style="position:relative; display: inline-block; cursor: pointer; font-size: 16px; line-height: 20px;">
                                <input type="checkbox" name="completed_assignments[]" value="<?= $assignment['assignment_id'] ?>">
                                <strong><?= htmlspecialchars($assignment['title']) ?>:</strong> <?= htmlspecialchars($assignment['question']) ?> (Deadline: <?= htmlspecialchars($assignment['deadline']) ?>)
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p>No assignments available for this course.</p>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary-gradient rounded-pill py-2 px-4 mt-4">Save Changes</button>
                </form>
            </div>
            <div class="quizzes">
                <h3 class="text-primary-gradient" style="margin-top: 20px;">Quizzes</h3>
                <?php if (count($courseDetails['quizzes']) > 0): ?>
                <ul class="list-unstyled">
                    <?php foreach ($courseDetails['quizzes'] as $quiz): ?>
                    <li class="d-flex mb-3">
                        <strong><?= htmlspecialchars($quiz['q_title']) ?>:</strong> <?= htmlspecialchars($quiz['q_description']) ?> (Date: <?= htmlspecialchars($quiz['q_date']) ?>)
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p>No quizzes available for this course.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="files">
                <h3>Files</h3>
                <?php if (count($courseDetails['files']) > 0): ?>
                    <ul>
                        <?php foreach ($courseDetails['files'] as $file): ?>
                            <li>
                                <strong><?= htmlspecialchars($file['title']) ?>:</strong> <?= htmlspecialchars($file['file_name']) ?> 
                                <a href="<?= htmlspecialchars($file['file_path']) ?>" download>Download</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No files available for this course.</p>
                <?php endif; ?>
                <form method="POST">
                <button type="submit" name="delete_course" class="btn btn-danger rounded-pill py-2 px-4 mt-4">Delete Course</button>
            </form>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="container-xxl py-5" id="feature" style="background-color:white;">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Upcoming Tasks</h5>
            <h1 class="mb-5">Stay on Track!</h1>
        </div>
        <div class="reminder-list wow fadeInUp" data-wow-delay="0.1s">
            <div class="bg-light rounded p-4 mb-4">
                <h3 class="text-primary-gradient mb-3">Today (<?php echo date('Y-m-d'); ?>)</h3>
                <ul class="list-unstyled">
                    <?php
                    $todayTasks = getAssignmentsAndQuizzes($conn, $userId, date('Y-m-d'));
                    foreach ($todayTasks as $task): ?>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            <?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bg-light rounded p-4 mb-4">
                <h3 class="text-primary-gradient mb-3">Tomorrow (<?php echo date('Y-m-d', strtotime('+1 day')); ?>)</h3>
                <ul class="list-unstyled">
                    <?php
                    $tomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, date('Y-m-d', strtotime('+1 day')));
                    foreach ($tomorrowTasks as $task): ?>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fa fa-check-circle text-warning me-2"></i>
                            <?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bg-light rounded p-4">
                <h3 class="text-primary-gradient mb-3">Day After Tomorrow (<?php echo date('Y-m-d', strtotime('+2 days')); ?>)</h3>
                <ul class="list-unstyled">
                    <?php
                    $dayAfterTomorrowTasks = getAssignmentsAndQuizzes($conn, $userId, date('Y-m-d', strtotime('+2 days')));
                    foreach ($dayAfterTomorrowTasks as $task): ?>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fa fa-check-circle text-danger me-2"></i>
                            <?php echo $task['title'] . ' (' . $task['course_name'] . ')'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="container-xxl py-5" id="pricing" style="background-color:white;">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Curricular Activities</h5>
            <h1 class="mb-5">Upcoming Events and Activities</h1>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
            <?php
            // Fetch curricular activities from the database using the function
            $activities = getCurricularActivities($conn, $userId); // Assuming the function fetches activities for the logged-in user
            foreach ($activities as $activity):
            ?>
                <div class="testimonial-item rounded p-4">
                    <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-calendar2-event-fill" style="font-size: 80px; color:lightgrey"></i>
                    
                        <div class="ms-4">
                            <h5 class="mb-1"><?= htmlspecialchars($activity['title']) ?></h5>
                            <p class="mb-1"><?= htmlspecialchars($activity['description']) ?></p>
                            <div>
                                <small class="fa fa-calendar text-primary"></small>
                                <small class="ms-1"><?= htmlspecialchars($activity['t_date']) ?></small>
                            </div>
                        </div>
                    </div>
                    <a href="<?= htmlspecialchars($activity['t_link']) ?>" target="_blank" class="btn btn-primary-gradient">View Event</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<div class="container-xxl py-5" id="review" style="background-color:white;">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Add Notions</h5>
            <h1 class="mb-5">Create a New Course/ Assignment/ Quiz</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <label for="event_type">Event Type</label>
                                    <select id="event_type" name="event_type" required class="form-control">
                                        <option value="course">Course</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="quiz">Quiz</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" id="course_name_section">
                                <div>
                                    <label for="course_name">Course Name</label>
                                    <select id="course_name" name="course_name" class="form-control">
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= htmlspecialchars($course['c_name']) ?>"><?= htmlspecialchars($course['c_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div>
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div>
                                    <label for="question">Question/Description</label>
                                    <textarea id="question" name="question" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="col-12" id="date_section">
                                <div>
                                    <label for="date">Date</label>
                                    <input type="date" id="date" name="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button class="btn btn-primary-gradient rounded-pill py-3 px-5" type="submit">Add</button>
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
    <div class="container-xxl py-5" id="upload-files" style="background-color:white;">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Upload Files</h5>
            <h1 class="mb-5">Upload Your Files Here</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <!-- File Title Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="File Title" required>
                                    <label for="title">File Title</label>
                                </div>
                            </div>

                            <!-- Course Name Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="course_name" id="course_name" class="form-control" required>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= htmlspecialchars($course['c_name']) ?>"><?= htmlspecialchars($course['c_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="course_name">Select Course</label>
                                </div>
                            </div>

                            <!-- File Upload Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="file" name="file" id="file" class="form-control" required>
                                    <label for="file">Choose File</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 text-center">
                                <button class="btn btn-primary-gradient rounded-pill py-3 px-5" type="submit" name="submit">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-xxl py-5" id="upload-files" style="background-color:white;">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Upload Event</h5>
            <h1 class="mb-5">Upload Your Event Details Here</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <form action="upload_event.php" method="POST">
                        <div class="row g-3">
                            <!-- Event Title Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Event Title" required>
                                    <label for="title">Event Title</label>
                                </div>
                            </div>

                            <!-- Event Description Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" placeholder="Event Description" required></textarea>
                                    <label for="description">Event Description</label>
                                </div>
                            </div>

                            <!-- Event Link Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="url" name="t_link" id="t_link" class="form-control" placeholder="Event Link" required>
                                    <label for="t_link">Event Link (URL)</label>
                                </div>
                            </div>

                            <!-- Event Date Field with form-floating -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="date" name="t_date" id="t_date" class="form-control" required>
                                    <label for="t_date">Event Date</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 text-center">
                                <button class="btn btn-primary-gradient rounded-pill py-3 px-5" type="submit" name="submit">Upload Event</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid bg-primary text-light footer wow fadeIn" data-wow-delay="0.1s" id="contact">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-3">
                        <h4 class="text-white mb-4">Address</h4>
                        <p><i class="fa fa-map-marker-alt me-3"></i>CSE470, Group 8</p>
                        <p><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">EduFlow</a>, All Right Reserved. 
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="#" class="btn btn-lg btn-lg-square back-to-top pt-2"><i class="bi bi-arrow-up text-white"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>