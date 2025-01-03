<?php
// Database connection
$servername = "localhost"; // replace with your server address if needed
$username = "root";        // replace with your database username
$password = "";            // replace with your database password
$dbname = "eduflow";       // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to avoid undefined variable errors
$name = $username = $email = $phone = $institution = $password = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $institution = mysqli_real_escape_string($conn, $_POST['institution']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // SQL query to insert data into login table
    $sql = "INSERT INTO login (email, name, username, phone, institution, password) 
            VALUES ('$email', '$name', '$username', '$phone', '$institution', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>New record created successfully.</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up Form</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="css/styleFORM.css">
</head>
<body>

    <div class="main">

        <div class="container">
            <div class="signup-content">
                <form method="POST" id="signup-form" class="signup-form">
                    <h2>Sign up</h2>
                    <p class="desc">to get discount 10% when pre-order <span>“Batman Beyond”</span></p>
                    <div class="form-group">
                        <input type="text" class="form-input" name="name" id="name" placeholder="Full Name" value="<?php echo $name; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-input" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-input" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-input" name="phone" id="phone" placeholder="Phone Number" value="<?php echo $phone; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-input" name="institution" id="institution" placeholder="Institution" value="<?php echo $institution; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-input" name="password" id="password" placeholder="Password" required/>
                        <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                        <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="form-submit submit" value="Sign up"/>
                        <a href="#" class="submit-link submit">Sign in</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body><!-- This template was made by Colorlib (https://colorlib.com) -->
</html>
